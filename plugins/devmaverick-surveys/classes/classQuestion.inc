<?php
/**
 * Use like this: $dm_question = new DM_Question;
 */
class DM_Question {

  /**
   * [get_question description]
   * @param  string $question_id [description]
   * @return array              Returns name, narrative, number, score, etc
   */
  public function get_question_block( $question_id = '' ) {

    if (!$question_id) return false;
    if ( in_array( $question_id, [4, 5] ) ) {
      return;
    }

      $dm_chart = new DM_Chart;

      $dm_current_question = $this -> get_question( $question_id );

      $question_name          = $dm_current_question -> q_short_text;
      $question_narrative     = $dm_current_question -> tab_narrative;
      $survey_question_number = $dm_current_question -> q_number; // This is the actual number of the question in the survey.

      $return = '<div class="dm-question-block dm-question-' . $question_id . '" question-id="' . $question_id . '">


                    <h4>' .  $question_name . '</h4>
                    <div class="question-narrative">' . $question_narrative . '</div>';

      $return .=  $dm_chart -> get_chart( $question_id );
      $return .=   $this -> get_open_ended_questions_block( $survey_question_number ) ;

      $return .=  '</div>';

      // echo '<pre>Open ended questions';
      // print_r( $this -> get_open_ended_questions_block( $survey_question_number ) );
      // echo '</pre>';

      return $return;
  }

  public function get_question_comparison_block( $question_id = '' ) {

    if (!$question_id) return false;

      $dm_chart = new DM_Chart;

      $dm_current_question = $this -> get_question( $question_id );

      // echo '<pre>$dm_current_question';
      // print_r( $dm_current_question );
      // echo '</pre>';


      $question_name          = $dm_current_question -> q_short_text;
      $question_narrative     = $dm_current_question -> comparison_narrative;

      $return .= '<div class="dm-comparison-question-block dm-question-' . $question_id . '" question-id="' . $question_id . '">';
      $return .=  '<h4>' . $question_name . '</h4>';
      $return .=  '<div class="question-narrative">' . $question_narrative . '</div>';

      $return .=  $dm_chart -> get_comparison_chart( $question_id );

      $return .= '</div>';

      return $return;
  }

  public function get_question( $question_id = '' ) {

    if (!$question_id) return false;

    global $global_all_questions;

    return $global_all_questions[$question_id];
  }

/**
 * Only used to generate de global function. NOt used directly in the script.
 * @return [type] [description]
 */
  public function get_all_questions_list( $iped = '') {

    if (!$iped) {
      $dm_school    = new DM_School;
      $iped = $dm_school -> get_iped(); // S-ar putea ca aici sa nu fie neaparat necesar.
    }

    global $wpdb;

    // Get the data from the 'dm_survey_responses' table
    $sql_all_questions_list = 'SELECT * FROM `dm_survey_all_questions` ';
    $all_questions_list = $wpdb->get_results( $sql_all_questions_list );

    $all_questions_list_by_id = array();
    foreach ($all_questions_list as $key => $question) {
      $all_questions_list_by_id[$question -> id] = $question;
    }

    /*
    Once we have that, also add the $responses_max and $responses_min to the same array
     */
    $responses_max       = get_responses_max_score();
    $responses_min       = get_responses_min_score();



    /*
    After that, get the averages and other data
     */
    $response_averages            = get_response_averages(); // TODO. Trebuie refacut
    $response_averages_by_iped    = get_response_averages_by_iped( $iped ); // OK - functie refacuta


    foreach ($all_questions_list as $key => $question) {
      $current_max = $responses_max[$question -> id] -> max;
      $current_min = $responses_min[$question -> id] -> min;
      $current_q_average        = $response_averages[$question -> id] -> average;
      $current_q_school_average = $response_averages_by_iped[$question -> id] -> school_ave;

      $all_questions_list_by_id[$question -> id] -> responses_max = $current_max;
      $all_questions_list_by_id[$question -> id] -> responses_min = $current_min;
      $all_questions_list_by_id[$question -> id] -> q_average         = $current_q_average;
      $all_questions_list_by_id[$question -> id] -> q_school_average  = $current_q_school_average;



      // Calculate the comparisons tab data
      $divizor = ( $current_max - $current_min);

      if ($divizor > 0) {
        $percent_lac = ($current_q_average - $current_min) / $divizor * 100;
        $percent_lac = round($percent_lac, 2);

        $percent_iped = ($current_q_school_average - $current_min) / $divizor * 100;
        $percent_iped = round($percent_iped, 2);

        $all_questions_list_by_id[$question -> id] -> percent_lac = $percent_lac;
        $all_questions_list_by_id[$question -> id] -> percent_iped = $percent_iped;
      } else {
        $all_questions_list_by_id[$question -> id] -> percent_lac = 0;
        $all_questions_list_by_id[$question -> id] -> percent_iped = 0;
      }
    }

    return $all_questions_list_by_id;
    // return $all_questions_list;

    // echo '<pre>';
    // print_r($all_questions_list);
    // echo '</pre>';

  }

  /*
  * Use like this: $open_ended_question_list = get_open_ended_questions_list();
  * NOTE: Only used to create the global variables, not anywhere else
  */
  public function get_open_ended_questions_list() {

    global $wpdb;

    // Get the data from the 'dm_survey_responses' table
    $sql_open_ended_list = 'SELECT * FROM `dm_survey_all_questions` WHERE other = "yes"';
    $open_ended_list = $wpdb->get_results( $sql_open_ended_list );

    $open_ended_list_by_id = array();
    foreach ($open_ended_list as $key => $question) {
      $open_ended_list_by_id[$question -> id] = $question;
    }

    return $open_ended_list_by_id;
    // return $open_ended_list;

    // echo '<pre>';
    // print_r($open_ended_list_by_id);
    // echo '</pre>';

  }


  private function get_open_ended_questions_block( $question_id = '') {

    if ( !$question_id ) return false;

    global $q_id_match;
    $q_all_id = $q_id_match[$question_id];



    $post_id = get_the_ID();
    $live_open_responses_by_question = get_post_meta($post_id, 'school_open_ended_responses', true);
    $current_question_open_responses = $live_open_responses_by_question[$q_all_id];
    // echo '<pre>';
    // print_r($current_question_open_responses);
    // echo '</pre>';




    if ($current_question_open_responses) {

      $return = '';

      $return .= '<div class="dm-what-students-say">';
      $return .= ' <h5>What students have to say? </h5>';


      $return .= '<table class="dm-students-say-table">
        <tr>
          <th class="dm-question-number">No.</th>
          <th class="dm-response-text">Reponse</th>
          <th class="dm-graduation-year">Graduation</th>
        </tr>';


      $count = 0;

      foreach ($current_question_open_responses as $key => $open_response) {
        $count++;

        $return .= '<tr>
                <td class="dm-question-number">' . $count . '</td>
                <td class="dm-response-text">' . $open_response['response_text'] . '</td>
                <td class="dm-graduation-year">' . $open_response['graduation_year'] . '</td>
              </tr>';
      }

      $return .= '</table>';
      $return .= '</div>';


  }


    return $return;

  }







  public function count_question_responses( $question_id = '') {

  }
  public function count_question_responses_by_iped( $question_id = '', $iped = '' ) {

  }



  public function get_question_options_list( $question_id = '' ) {

    if (!$question_id) return false;

    global $wpdb;

    // Get the data from the 'dm_survey_responses' table
    $sql_options_list= 'SELECT q_id_all, option_text, option_score FROM `dm_survey_options` WHERE q_id_all = ' . $question_id;
    $options_list = $wpdb->get_results( $sql_options_list );
    // $response_count = $response_count -> count;

    $options_list_by_id = array();
    foreach ($options_list as $key => $value) {
      $options_list_by_id[$value -> q_id_all][$value -> option_score] = $value;
    }

    return $options_list_by_id;

    // echo '<pre>';
    // print_r($options_list_by_id);
    // echo '</pre>';



  }

  /**
   * [ajax_save_open_question description]
   * @return [type] Always return the featured questions block
   */
  public function ajax_save_open_question() {

    $dm_meta_box = new DM_MetaBox;

    $ajax_data = $_POST['data'];
  	$post_id = $ajax_data['post_id'];
  	$open_response_array = $ajax_data;

  	unset($open_response_array['post_id']);

  	$full_array = get_post_meta($post_id, 'school_open_ended_responses', true);

  	$full_array[ $open_response_array['q_all_number'] ][ $open_response_array['respondent_id'] ] = $open_response_array;

  	update_post_meta($post_id, 'school_open_ended_responses', $full_array);

    $featured_answers_block = $dm_meta_box -> get_featured_answers_block( $post_id ); // TODO - De inlocuit cu noua functie care face tot ce trebuie

    return $featured_answers_block;

  }


  /**
   * [ajax_save_open_question description]
   * @return [type] Always return the featured questions block
   */
  public function ajax_remove_open_question() {

    $dm_meta_box = new DM_MetaBox;

    $ajax_data = $_POST['data'];

    // print_r( $ajax_data );
    $post_id      = $ajax_data['post_id'];
    $question_id  = $ajax_data['question_id'];
    $respondent_id  = $ajax_data['respondent_id'];

    $featured_answers = get_post_meta($post_id, 'school_open_ended_responses', true);

    // print_r($featured_answers);

    if ( $featured_answers[$question_id][$respondent_id] !== '' ) {
      unset( $featured_answers[$question_id][$respondent_id] );

      //Unset the parent value, if no more children
      if ( empty( $featured_answers[$question_id] ) ) {
        unset( $featured_answers[$question_id] );
      }
    }


    update_post_meta($post_id, 'school_open_ended_responses', $featured_answers);

    $featured_answers_block = $dm_meta_box -> get_featured_answers_block( $post_id ); // TODO - De inlocuit cu noua functie care face tot ce trebuie

    return $featured_answers_block;

  }

  public function match_q_id_to_q_all_id() {
    $sql = 'SELECT id, q_number FROM `dm_survey_all_questions` WHERE other = "yes"';
    global $wpdb;
    $q_id_correspondence = $wpdb->get_results( $sql );


    $q_id_match = array();
    foreach ($q_id_correspondence as $key => $value ) {
      // $q_id_match[$value -> q_number] = $value -> id;
      $q_id_match[$value -> q_number] = $value -> id;
    }
    $custom_string =  implode($q_id_match,',');

    return $q_id_match;
    // echo '<pre>Match 1';
    // print_r( $q_id_match );
    // echo '</pre>';
    //
    //
    // $sql2 = 'SELECT q_number, id  FROM `dm_survey_all_questions` WHERE other = "" AND q_number in (' . $custom_string . ')';
    // $q_id_correspondence2 = $wpdb->get_results( $sql2 );
    //
    //
    // $q_id_match2 = array();
    // foreach ($q_id_correspondence2 as $key => $value ) {
    //   // $q_id_match[$value -> q_number] = $value -> id;
    //   $q_id_match2[$value -> id] = $value -> q_number;
    // }
    //
    //
    // echo $sql2;
    // echo '<pre>Match 2';
    // print_r( $q_id_match2 );
    // echo '</pre>';
    //
    // return $q_id_match;
  }




} // End class
 ?>
