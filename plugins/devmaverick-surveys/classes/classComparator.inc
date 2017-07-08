<?php
/**
 * Use like this: $dm_comparator = new DM_Comparator;
 */
class DM_Comparator {




  /**
   *
   */
  public function compare_colleges_select_block() {

    $colleges_dropdown = $this -> get_available_colleges_dropdown();

    $return = '<div class="dm-compare-colleges-select-block">
                  <div class="col-sm-6 dm-school-1-container">School 1
                  ' . $colleges_dropdown . '
                  </div>
                  <div class="col-sm-6 dm-school-2-container">School 2
                  ' . $colleges_dropdown . '
                  </div>
                  <div class="">
                    <button type="button" class="btn btn-primary btn-lg dm-load-college-comparison-btn trigger-load-college-comparison-block">Compare</button>

                  </div>
              </div>';
    $return .= '<div class="dm-compare-colleges-block-container"></div>';

    // $return .= $this -> college_comparison_block();

    return $return;

  }


  public function college_comparison_block( $college_1 = '', $college_2 = '' ) {

    // $college_1 = 1568;
    // $college_2 = 1587;

    if ( ! $college_1 ) {
      return false;
    }
    if ( ! $college_2 ) {
      return false;
    }

    // $college_comparison_data['college_1'] = get_post_meta( $college_1, 'dm_college_comparator_data', true );
    // $college_comparison_data['college_2'] = get_post_meta( $college_2, 'dm_college_comparator_data', true );

    $settings = get_option( 'dm_college_comparison_settings' );

    // echo '<pre>';
    // print_r ($settings);
    // echo '</pre>';
    $college_comparison_data['college_1'] = $this -> calculate_college_comparator_data( $college_1, $settings );
    $college_comparison_data['college_2'] = $this -> calculate_college_comparator_data( $college_2, $settings );

    $college_1_image = get_the_post_thumbnail( $college_1, 'medium' );
    $college_2_image = get_the_post_thumbnail( $college_2, 'medium' );
    // echo '<pre>';
    // print_r ( $gigi );
    // echo '</pre>';

    $return = '<div class="dm-college-comparison-block">
                  <div class="dm-college-comparison-block-images">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-3 dm-college-image-holder">
                      ' . $college_1_image . '
                    </div>
                    <div class="col-sm-3 dm-college-image-holder">
                      ' . $college_2_image . '
                    </div>
                  </div>

                  <div class="dm-college-comparison-block-head">
                    <div class="col-sm-6">
                      Category
                    </div>
                    <div class="col-sm-3">
                      ' . get_the_title( $college_1 ) . '
                    </div>
                    <div class="col-sm-3">
                      ' . get_the_title( $college_2 ) . '
                    </div>
                  </div>
                  ';

    foreach ($settings as $key => $question_settings) {
      // If the question ID is not an integer, then it's not selected. Just skip this setting.
      if ( ! is_numeric ($question_settings['question_id']) ) {
        continue;
      }
      // If the response is empty, then there's nothing to search in the database for. Just skip this setting.
      if ( $question_settings['response'] == '' ) {
        continue;
      }

      $return .= $this -> college_comparison_row( $settings, $college_comparison_data, $key );

    }

    $return .= '</div>';

    return $return;

  }

  public function college_comparison_row( $settings = '', $college_comparison_data = '', $key = '' ) {

    // echo '<pre>';
    // print_r ( $college_comparison_data );
    // echo '</pre>';

    $college_1_data = $college_comparison_data['college_1'];
    $college_2_data = $college_comparison_data['college_2'];

    $college_1_percentage = round ( ( $college_1_data[$key]['matching_count'] / $college_1_data[$key]['total_count'] ) * 100 );
    $college_2_percentage = round ( ( $college_2_data[$key]['matching_count'] / $college_2_data[$key]['total_count'] ) * 100 );

    $difference = $college_1_percentage - $college_2_percentage;

    if ( $difference >= 10 ) {
      $major_difference_star_1 = '<span class="dm-major-difference"><i class="fa fa-star" aria-hidden="true"></i></span>';
    }
    if ( $difference <= -10 ) {
      $major_difference_star_2 = '<span class="dm-major-difference"><i class="fa fa-star" aria-hidden="true"></i></span>';
    }


    $return = '<div class="dm-comparison-question-row">
                <div class="col-sm-6">
                  <strong>' . $settings[$key]['category'] . ':</strong>
                  <span>' . $settings[$key]['pretty_response'] . '</span>
                </div>
                <div class="col-sm-3">
                  ' . $college_1_percentage . '%
                  ' . $major_difference_star_1 . '
                </div>
                <div class="col-sm-3">
                  ' . $college_2_percentage . '%
                  ' . $major_difference_star_2 . '
                </div>
              </div>';
    return $return;

    // echo '<pre>';
    // print_r ( $settings[$key] );
    // echo '</pre>';


  }


  private function get_available_colleges_dropdown() {

    $sql = 'SELECT post_id FROM `wp_postmeta` WHERE meta_key = "school_iped" AND meta_value <> ""';

    global $wpdb;
    $college_ids = $wpdb -> get_col( $sql );


    $args = array(
      	'posts_per_page'   => -1,
      	'offset'           => 0,
      	'category'         => '',
      	'category_name'    => '',
      	'orderby'          => 'title',
      	'order'            => 'ASC',
      	'include'          => '',
      	'exclude'          => '',
      	'meta_key'         => '',
      	'meta_value'       => '',
      	'post_type'        => 'schools',
      	'post_mime_type'   => '',
      	'post_parent'      => '',
      	'author'	   => '',
      	'author_name'	   => '',
      	'post_status'      => 'publish',
      	'suppress_filters' => true,
        'post__in'         => $college_ids
      );
    $colleges_array = get_posts( $args );

    // Build the select
    $return = '<select>';
    foreach ($colleges_array as $key => $college) {
      $return .= '<option value="' . $college -> ID . '">' . $college -> post_title . '</option>';
    }
    $return .= '</select>';

    return $return;

    // echo '<pre>';
    // print_r ( $posts_array );
    // echo '</pre>';

  }


  private function  get_fixed_response_questions_dropdown( $key = '', $selected = '' ) {
    $dm_question = new DM_Question;
    $open_fixed_response_questions_list = $dm_question -> get_fixed_response_questions_list();

    // Build the select
    $return = '<select name="question-' . $key . '">';
    $return .= '<option>--Select a question</option>';
    foreach ($open_fixed_response_questions_list as $key => $question) {

      $is_selected = ( $question -> id == $selected ) ? ' selected="selected" ' : '';
      $return .= '<option value="' . $question -> id . '" ' . $is_selected . '>' . $question -> q_short_text . '</option>';
    }
    $return .= '</select>';

    return $return;

    // echo '<pre>';
    // print_r ( $open_fixed_response_questions_list );
    // echo '</pre>';
  }

  public function get_responses_dropdown_for_question( $q_all_number = '', $row = '', $selected = '' ) {
    $sql = 'SELECT DISTINCT response FROM dm_survey_responses WHERE `q_all_number` = ' . $q_all_number;

    global $wpdb;
    $responses = $wpdb -> get_col( $sql );


    // Build the select
    $return = '<select name="response-' . $row . '">';
    // $return .= '<option>--Select a response</option>';
    foreach ( $responses as $key => $response ) {
      $is_selected = ( $selected == $response ) ? ' selected="selected" ' : '';
      $return .= '
                  <option value="' . $response . '" ' . $is_selected . '>' . $response . '</option>';
    }
    $return .= '</select>';

    return $return;



    echo '<pre>';
    print_r ( $responses );
    echo "</pre>";

  }






  /**
   * Admin Setup Section
   */

  public function setup_question_block() {



    $return = '<div class="dm-setup-comparator-questions">
                <form class="dm-setup-comparator-form">';

    $return .= '<div class="dm-setup-comparator-question-row dm-title-row">
                  <div class="dm-category">
                    Category
                  </div>
                  <div class="dm-question">
                    Question to use
                  </div>
                  <div class="dm-response">
                    Targeted Response
                  </div>
                  <div class="dm-pretty-response">
                    Pretty Response
                  </div>
              </div>';

    for ($i=1; $i < 21; $i++) {
      $settings = get_option( 'dm_college_comparison_settings' );
      $return .= $this -> setup_question_row( $i, $settings );
    }

    $return .= '</form>
              <input name="save" type="submit" class="button button-primary button-large dm-save-button trigger-save-comparator-setup" id="save" value="Save Setup">
              </div>';

    return $return;
  }



  private function setup_question_row( $key = '', $settings = '' ) {

    $questions_dropdown = $this -> get_fixed_response_questions_dropdown( $key, $settings[$key]['question_id']  );
    if ( $settings[$key]['question_id'] != '' ) {
      $responsed_dropdown = $this -> get_responses_dropdown_for_question( $settings[$key]['question_id'], $key, $settings[$key]['response'] );
    }


    $return = '<div class="dm-setup-comparator-question-row" question-id="' . $key . '">
                  <div class="dm-category">
                    <input type="text" name="category-' . $key . '" value="' . $settings[$key]['category'] . '">
                  </div>
                  <div class="dm-question" name="question-' . $key . '">
                    ' . $questions_dropdown . '
                  </div>
                  <div class="dm-response" name="response-' . $key . '">
                    ' . $responsed_dropdown . '
                  </div>
                  <div class="dm-pretty-response">
                    <input type="text" name="pretty-response-' . $key . '" value="' . $settings[$key]['pretty_response'] . '">
                  </div>
              </div>';

    return $return;
  }

  public function ajax_save_comparator_setup() {
    $params = array();
    parse_str( $_POST['data'], $params);

    for ($i=1; $i < 21; $i++) {
      $options[$i]['category']        = $params['category-' . $i];
      $options[$i]['question_id']     = $params['question-' . $i];
      $options[$i]['response']        = $params['response-' . $i];
      $options[$i]['pretty_response'] = $params['pretty-response-' . $i];
    }

    $result = update_option( 'dm_college_comparison_settings', $options, false );

    return $result;

    // print_r ( $options );
  }




  public function calculate_college_comparator_data( $college_id = '', $settings = '' ) {

    if ( ! $settings ) {
      $settings = get_option( 'dm_college_comparison_settings' );
    }

    // Get the school IPED
    $dm_school = new DM_School;
    $iped = $dm_school -> get_iped( $college_id );


    foreach ($settings as $key => $question_settings) {
      // If the question ID is not an integer, then it's not selected. Just skip this setting.
      if ( ! is_numeric ($question_settings['question_id']) ) {
        continue;
      }
      // If the response is empty, then there's nothing to search in the database for. Just skip this setting.
      if ( $question_settings['response'] == '' ) {
        continue;
      }

      $q_all_number = $question_settings['question_id'];
      $response     = $question_settings['response'];

      global $wpdb;

      // 1. Get Total number of responses for current question and IPED
      $sql = "SELECT COUNT(*) FROM `dm_survey_responses` WHERE iped = " . $iped . " AND q_all_number = " . $q_all_number;

      // echo $sql . '<br />';

      $result = $wpdb -> get_col( $sql );
      $total_count = $result[0];

      // echo '<pre>';
      // print_r ( $college_ids );
      // echo '</pre>';
      // echo $college_ids . '<br />';

      // 2. Get number of matching responses for current question and IPED

      $sql = "SELECT COUNT(*) FROM `dm_survey_responses` WHERE iped = " . $iped . " AND q_all_number = " . $q_all_number . " AND response = '" . $response . "'";

      // echo $sql . '<br /><br />';
      $result = $wpdb -> get_col( $sql );
      $matching_count = $result[0];
      // echo '<pre>';
      // print_r ( $college_ids );
      // echo '</pre>';
      // echo $college_ids . '<br />';

      $college_comparator_data[$key]['matching_count'] = $matching_count;
      $college_comparator_data[$key]['total_count'] = $total_count;


    }

    return $college_comparator_data;
    // TODO De verificat si ce-i mai jos. Dar asta (returnul) am facut-o ca sa nu mai pierdem vremea cu procesarea.

    // Save the post meta
    update_post_meta( $college_id, 'dm_college_comparator_data', $college_comparator_data );

    echo 'Updated Comparator Data for College #' . $college_id . ': ' . get_the_title( $college_id );

    echo '<pre>';
    print_r ( $college_comparator_data );
    echo '</pre>';

  }


  public function update_comparator_data_for_all_schools() {
    $sql = 'SELECT post_id FROM `wp_postmeta` WHERE meta_key = "school_iped"';

    global $wpdb;
    $college_ids = $wpdb -> get_col( $sql );

    foreach ($college_ids as $key => $college_id) {
      $this -> calculate_college_comparator_data( $college_id );
    }


    // echo '<pre>';
    // print_r ( $college_ids );
    // echo '</pre>';
  }

} // End Class

?>
