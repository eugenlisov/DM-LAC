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
                  <div class="col-sm-6 dm-school-1-container">
                  ' . $colleges_dropdown . '
                  </div>
                  <div class="col-sm-6 dm-school-2-container">
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

    // $college_1 = 209;
    // $college_2 = 479;

    if ( ! $college_1 ) {
      return false;
    }
    if ( ! $college_2 ) {
      return false;
    }

    if ( $college_1 == $college_2 ) {
      return 'Chose two different colleges, please!';
    }

    $settings = get_option( 'dm_college_comparison_settings' );

    // echo '<pre>';
    // print_r ( $settings );
    // echo '</pre>';

    $college_comparison_data = $this -> calculate_college_comparator_data( $college_1, $college_2, $settings );

    $college_1_image = get_the_post_thumbnail( $college_1, 'Comparator_Thumb' );
    $college_2_image = get_the_post_thumbnail( $college_2, 'Comparator_Thumb' );

    $return = '<div class="dm-college-comparison-block">
                  <div class="dm-college-comparison-block-images">
                    <div class="col-sm-6 dm-comparator-narrative">
                      <div>
                        ' . $settings['comparator_narrative'] . '
                      </div>
                    </div>
                    <div class="col-sm-6" style="padding: 0;">
                        <div class="col-sm-11 col-sm-offset-1" style="padding: 0;">
                            <div class="col-sm-6 dm-college-image-holder">
                              <a href="' . get_permalink( $college_1 ) . '">' . $college_1_image . '</a>
                            </div>
                            <div class="col-sm-6 dm-college-image-holder">
                              <a href="' . get_permalink( $college_2 ) . '">' . $college_2_image . '</a>
                            </div>
                        </div>
                    </div>

                  </div>

                  <div class="dm-college-comparison-block-head">
                    <div class="col-md-11 col-md-offset-1">
                      <div class="col-sm-6">
                        Category
                      </div>
                      <div class="col-sm-3">
                        <a href="' . get_permalink( $college_1 ) . '">' . get_the_title( $college_1 ) . '</a>
                      </div>
                      <div class="col-sm-3">
                        <a href="' . get_permalink( $college_2 ) . '">' . get_the_title( $college_2 ) . '</a>
                      </div>
                    </div>
                  </div>
                  ';


    $community_block = '';
    $academics_block = '';
    $social_life_block = '';

    foreach ($settings as $key => $question_settings) {
      // If the question ID is not an integer, then it's not selected. Just skip this setting.
      if ( ! is_numeric ($question_settings['question_id']) ) {
        continue;
      }
      // If the response is empty, then there's nothing to search in the database for. Just skip this setting.
      if ( $question_settings['response'] == '' ) {
        continue;
      }

      $current_row = $this -> college_comparison_row( $settings, $college_comparison_data, $key, $college_1, $college_2 );

      switch ( $question_settings['selected_tab'] ) {
        case 2:
          $community_block .= $current_row;
          break;
        case 3:
          $academics_block .= $current_row;
          break;
        case 4:
          $social_life_block .= $current_row;
          break;

      }

    } // End foreach

    $return .= '<div class="dm-main-category">
                  <div class="col-md-1">
                    <span>
                      <i class="fa fa-users" aria-hidden="true"></i>
                      <span>Community</span>
                    </span>
                  </div>
                  <div class="col-md-11">' . $community_block . '</div>
                </div>';
    $return .= '<div class="dm-main-category">
                  <div class="col-md-1">
                    <span>
                      <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                      <span>Academics</span>
                    </span>
                  </div>
                  <div class="col-md-11">' . $academics_block . '</div>
                </div>';
    $return .= '<div class="dm-main-category">
                  <div class="col-md-1">
                    <span>
                      <i class="fa fa-weixin" aria-hidden="true"></i>
                      <span>Social life</span>
                    </span>
                  </div>
                  <div class="col-md-11">' . $social_life_block . '</div>
                </div>';

    $return .= '</div>';


    $return .= '<div class="dm-comparator-sub-notes">
                    <div class="dm-key">
                      KEY
                    </div>
                    <div class="dm-values">
                        <div class="dm-sub-notes-item">
                          <img src="' . SURVEYS_ASSETS_URL  . '/images/big-six.png">
                          A "Big 6" Factor based on <a href="' . get_permalink( PAGE_VOICE_OF_STUDENT ) . '">' . get_the_title( PAGE_VOICE_OF_STUDENT ) . '</a>
                        </div>

                        <div class="dm-sub-notes-item">
                          <span class="dm-significand-difference-icon">
                            <i class="fa fa-star" aria-hidden="true"></i>
                          </span>
                          Category Winner by a Significant Margin
                        </div>
                    </div>
                </div>';
    return $return;

  }

  public function college_comparison_row( $settings = '', $college_comparison_data = '', $key = '', $college_1 = '', $college_2 = '' ) {

    // echo '<pre>';
    // print_r ( $college_comparison_data );
    // echo '</pre>';

    foreach ($college_comparison_data as $key_college => $collage_data) {
      if ( ! $college_1_data ) {
        $college_1_data = $collage_data;
        continue;
      }
      if ( ! $college_2_data ) {
        $college_2_data = $collage_data;
      }
    }
    $current_question_id = $settings[$key]['question_id'];

    // echo '$current_question_id: ' . $current_question_id . '<br />';

    $college_1_data = $college_comparison_data[$college_1];
    $college_2_data = $college_comparison_data[$college_2];
    // echo '<pre>';
    // print_r ( $college_comparison_data );
    // echo '</pre>';
    // echo '<pre>';
    // print_r ( $college_1_data );
    // echo '</pre>';
    // echo '<pre>';
    // print_r ( $college_2_data );
    // echo '</pre>';

    $college_1_percentage = round ( ( $college_1_data[$current_question_id]['matching_count'] / $college_1_data[$current_question_id]['total_count'] ) * 100 );
    $college_2_percentage = round ( ( $college_2_data[$current_question_id]['matching_count'] / $college_2_data[$current_question_id]['total_count'] ) * 100 );



    if ( $settings[$key]['reverse_target'] != true ) {
      $difference = $college_1_percentage - $college_2_percentage;
    } else {
      $difference = $college_2_percentage - $college_1_percentage;
    }

    $signifficant_difference = $settings['signifficant_difference'];
    // echo $settings[$key]['category'] . '<br />';
    // echo '$college_1_percentage: ' . $college_1_percentage . '<br />';
    // echo '$college_2_percentage: ' . $college_2_percentage . '<br />';
    //
    // echo '<pre>';
    // print_r ( $settings[$key]['reverse_target'] );
    // echo '</pre>';
    // echo $difference . '<br />';

    if ( $difference >= $signifficant_difference ) {
      $major_difference_star_1 = '<span class="dm-major-difference"><i class="fa fa-star" aria-hidden="true"></i></span>';
    }
    if ( $difference <= - $signifficant_difference ) {
      $major_difference_star_2 = '<span class="dm-major-difference"><i class="fa fa-star" aria-hidden="true"></i></span>';
    }
    $bix_six_block = '<div class="dm-big-six"><img src="' . SURVEYS_ASSETS_URL  . '/images/big-six.png"></div>';
    $big_six = ( $settings[$key]['big_six'] == true ) ? $bix_six_block : false;
    if ( $big_six ) {
      $big_six_class = 'dm-has-big-six ';
    }


    $return = '<div class="dm-comparison-question-row">
                <div class="col-sm-6 ' . $big_six_class . '">
                  <strong>' . $settings[$key]['category'] . ':</strong>
                  <span>' . $settings[$key]['pretty_response'] . '</span>
                  ' . $big_six . '
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
    global $wpdb;

    $sql_distinct_ipeds = 'SELECT DISTINCT(iped) FROM `dm_survey_responses`';
    $distinct_ipeds = $wpdb -> get_col( $sql_distinct_ipeds );
    $distinct_ipeds_string = implode( ",", $distinct_ipeds);

    $sql = 'SELECT post_id FROM `wp_postmeta` WHERE meta_key = "school_iped" AND meta_value IN (' . $distinct_ipeds_string . ')';
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
      $return .= '<option value="" disabled selected>-- select college --</option>';
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

      // echo 'Selected:' . stripslashes( $selected ) . ' <br />';
      // echo 'Response:' . stripslashes( $response ) . ' <br /><br />';

      $is_selected = ( stripslashes( $selected ) == stripslashes( $response ) ) ? ' selected="selected" ' : '';
      $return .= '
                  <option value="' . $response . '" ' . $is_selected . '>' . $response . '</option>';
    }
    $return .= '</select>';

    return $return;



    // echo '<pre>';
    // print_r ( $responses );
    // echo "</pre>";

  }






  /**
   * Admin Setup Section
   */

  public function setup_question_block() {

    $settings = get_option( 'dm_college_comparison_settings' );

    // echo '<pre>';
    // print_r( $settings );
    // echo '</pre>';

    $return = '<div class="dm-setup-comparator-questions">
                <form class="dm-setup-comparator-form">';

    $return .= '<div class="row">
                  <h3>Comparator Narrative</h3>
                  <div class="dm-setup-comparator-question-row dm-title-row">
                    <textarea rows="4" cols="50"  name="comparator-narrative" class="dm-comparator-narrative" >' . $settings['comparator_narrative'] . '</textarea>
                  </div>
                </div>';

    $return .= '<div class="row">
                  <h3>Signifficant Difference Value</h3>
                  <div class="dm-setup-comparator-question-row dm-title-row">
                    <input type="number" name="signifficant-difference" value="' . $settings['signifficant_difference'] . '" class="dm-signifficant-difference">
                  </div>
                </div>';

    $return .= '
                <h3>Comparator Questions</h3>
                <div class="dm-setup-comparator-question-row dm-title-row">
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
                  <div class="dm-response-positive">
                    Positive/Negative
                  </div>
                  <div class="dm-main-category">
                    Main Category
                  </div>
                  <div class="dm-big-six">
                    Bix Six
                  </div>
              </div>';

    for ($i=1; $i < 21; $i++) {

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

    $selected_tab = $settings[$key]['selected_tab'];

    $checked = ( $settings[$key]['reverse_target'] == true ) ? 'checked' : '';
    $checked_bix_six = ( $settings[$key]['big_six'] == true ) ? 'checked' : '';

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
                  <div class="dm-response-positive">
                    <label><input type="checkbox" name="reverse-target-' . $key . '" value="true" ' . $checked . '> Reverse</label>
                  </div>
                  <div class="dm-main-category">
                    ' . $this -> main_category_dropdown( $key, $selected_tab ) . '
                  </div>
                  <div class="dm-big-six">
                    <label><input type="checkbox" name="big-six-' . $key . '" value="true" ' . $checked_bix_six . '></label>
                  </div>
              </div>';

    return $return;
  }

  // TODO Later make this dynamic.
  public function main_category_dropdown( $current_key = '', $selected_tab = '' ) {

    $main_categogories = array(
      2 => 'Community',
      3 => 'Academics',
      4 => 'Social Life',
    );

    // echo '<pre>';
    // print_r( $main_categogories );
    // echo '</pre>';

    $return = '<select name="selected-tab-' . $current_key . '">';
    $return .= '<option disabled>select an option</option>';
    foreach ($main_categogories as $key => $category) {
      $selected = ( $selected_tab == $key ) ? 'selected' : false;
      $return .= '<option value="' . $key . '" ' . $selected . '>' . $category . '</option>';
    }

    $return .= '</select>';

    return $return;
  }

  public function ajax_save_comparator_setup() {
    $params = array();
    parse_str( $_POST['data'], $params);

      $options['comparator_narrative']    = $params['comparator-narrative'];
      $options['signifficant_difference'] = $params['signifficant-difference'];

    for ($i=1; $i < 21; $i++) {
      $options[$i]['category']        = $params['category-' . $i];
      $options[$i]['question_id']     = $params['question-' . $i];
      $options[$i]['response']        = $params['response-' . $i];
      $options[$i]['pretty_response'] = $params['pretty-response-' . $i];
      $options[$i]['reverse_target']  = $params['reverse-target-' . $i];
      $options[$i]['selected_tab']    = $params['selected-tab-' . $i];
      $options[$i]['big_six']         = $params['big-six-' . $i];
    }

    $result = update_option( 'dm_college_comparison_settings', $options, false );

    return $result;

  }


  /**
   * Optimised function
   */
  public function calculate_college_comparator_data( $college_1 = '', $college_2 = '', $settings = '' ) {

    if ( ! $college_1  || ! $college_2 ) {
      return false;
    }
    if ( ! $settings ) {
      $settings = get_option( 'dm_college_comparison_settings' );
    }

    $colleges[] = $college_1;
    $colleges[] = $college_2;

    // Get the school IPEDs
    $dm_school = new DM_School;
    foreach ($colleges as $key => $college_id) {
      $ipeds_array[$key] = $dm_school -> get_iped( $college_id );
    }
    $ipeds = implode (', ', $ipeds_array );

    // Create an array of the q_all_number IDs, from the settings fields
    // Create an array of responses, from the settings fields

    foreach ($settings as $key => $question_settings) {
      // If the question ID is not an integer, then it's not selected. Just skip this setting.
      if ( ! is_numeric ($question_settings['question_id']) ) {
        continue;
      }
      // If the response is empty, then there's nothing to search in the database for. Just skip this setting.
      if ( $question_settings['response'] == '' ) {
        continue;
      }
      $question_id_array[] = $question_settings['question_id'];
      $responses_array[] = '"' . $question_settings['response'] . '"';
    }
    // If there are no questions, there's no point in continuing.
    if ( ! $question_id_array ){
      return false;
    }
    $question_ids = implode (', ', $question_id_array );
    $responses = implode (', ', $responses_array );

    $sql_total = 'SELECT iped, q_all_number, COUNT(*) AS total_count
                    FROM `dm_survey_responses`
                    WHERE iped IN ( ' . $ipeds . ')
                      AND q_all_number IN ( ' . $question_ids . ' )
                    GROUP BY iped, q_all_number';
    $sql_matching = 'SELECT iped, q_all_number, COUNT(*) AS matching_count
                    FROM `dm_survey_responses`
                    WHERE iped IN ( ' . $ipeds . ')
                      AND q_all_number IN ( ' . $question_ids . ' )
                      AND response IN ( ' . $responses . ' )
                    GROUP BY iped, q_all_number';

    // Do the DB Queries
    global $wpdb;

    $total_results = $wpdb -> get_results( $sql_total );
    $matching_results = $wpdb -> get_results( $sql_matching );

    // Organize the DB results into arrays

    foreach ($total_results as $key => $result) {
      $total_array[$result -> iped][$result -> q_all_number] = $result -> total_count;
    }
    foreach ($matching_results as $key => $result) {
      $matching_array[$result -> iped][$result -> q_all_number] = $result -> matching_count;
    }

    // Now build the return array;
    foreach ($colleges as $key_college => $college_id) {

          foreach ($question_id_array as $key_question => $question_id) {
            $return_array[$college_id][$question_id]['matching_count']  = $matching_array[ $ipeds_array[$key_college] ][$question_id];
            $return_array[$college_id][$question_id]['total_count']     = $total_array[ $ipeds_array[$key_college] ][$question_id];

             // aici merge salvat si un custom field care sa tina minte, fara a mai face odata calculele pentru fiecare scoala.
             // Apoi, dupa fiecare import, sa se scterga toate custom fieldurile cu valorile astea si sa se ia de la capat la fiecare regenerare
             // Cum facea candva asta: update_post_meta( $college_id, 'dm_college_comparator_data', $college_comparator_data );

          } // End foreach loop 2

    } // End foreach loop 2

    return $return_array;

  }

} // End Class

?>
