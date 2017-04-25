<?php
/**
 * Use like this: $dm_meta_box = new DM_MetaBox;
 */
class DM_MetaBox {


    public function get_open_ended_questions_meta_box( $post = '' ) {

      $return = '';
      $return .= $this -> get_featured_answers_block( $post -> ID );
      $return .= $this -> get_available_answers_block( $post -> ID );

      wp_enqueue_style('dm-meta-boxes-style', plugins_url() . '/devmaverick-surveys/assets/css/back/meta-boxes.css');
      wp_enqueue_script('dm-schools-script', plugins_url() . '/devmaverick-surveys/assets/js/back/meta-boxes.js', array(), '1.0.0', true );

      return $return;
    }



    public function get_featured_answers_block( $post_id = '' ) {
      if (!$post_id) return false;
      global $wpdb;

      $open_responses_by_question = get_post_meta($post_id, 'school_open_ended_responses', true);

      // echo '<pre>$open_responses_by_question';
      // print_r( $open_responses_by_question );
      // echo '</pre>';

      $q_all_string = implode (", ", array_keys( $open_responses_by_question ));
      $sql_question_names = 'SELECT id, q_short_text FROM dm_survey_all_questions WHERE id IN (' . $q_all_string . ')';

      $question_names = $wpdb->get_results( $sql_question_names );
      $question_names_by_id = [];

      foreach ($question_names as $key => $value) {
        $question_names_by_id[$value -> id] = $value -> q_short_text;
      }

      $return_string .= '<div class="dm-existing-open-ended-questions">
                          <h4>These are the open ended responses you\'ve selected.</h4>';

      if ( !empty($open_responses_by_question) ) {
        foreach ($open_responses_by_question as $question_id => $responses) {

          // echo '<pre>$responses';
          // print_r( $responses );
          // echo '</pre>';

          if(!empty($responses)) {

              $return_string .= '

              <div class="dm-open-ended-question" question-number=" ' . $question_id . '">
               <table>
                 <tbody>
                   <tr>
                     <th class="dm-question-number">' . $question_id . '</th>
                     <th class="dm-response-text">' . $question_names_by_id[$question_id] . '</th>
                     <th class="dm-graduation-year">Graduation year</th>
                     <th class="dm-feature-checkbox">Action</th>
                   </tr>';

                     foreach ($responses as $response_id => $response) {

                       $respondent_id    = $response['respondent_id'];
                       $response_text    = $response['response_text'];
                       $graduation_year  = ( $response['graduation_year'] == "0" ) ? '' : $response['graduation_year'];

                       $return_string .=   '
                             <tr class="dm-data-holder" respondent-id="' . $respondent_id . '" q_all_number="' . $question_id . '">
                               <td class="dm-question-number"></td>
                               <td class="dm-response-text">' . stripslashes  ($response_text) . '</td>
                               <td class="dm-graduation-year">' . $graduation_year. '</td>
                               <td class="dm-feature-checkbox"> <span class="remove-it">Remove!</span></td>
                             </tr>';
                     }

             $return_string .= '

                 </tbody>
               </table>
             </div>';

          }
        }
      }
      $return_string .= '</div>';

       return $return_string;

    }



    private function get_available_answers_block( $post_id = '' ) {

      $live_open_responses_by_question = get_post_meta($post_id, 'school_open_ended_responses', true);

      $dm_question                        = new DM_Question;
      $global_open_ended_question_list    = $dm_question -> get_open_ended_questions_list();
      $iped                               = get_post_meta( $post_id, 'school_iped', true ); // TODO de inlocuit
      $selected_open_ended_questions      = get_post_meta( $post_id, 'dm_open_ended_questions', true );
      $responses_by_question              = get_open_eded_responses_by_iped_and_question( $iped );


      // echo '<pre>$live_open_responses_by_question';
      // print_r($live_open_responses_by_question);
      // echo '</pre>';
      //
      // echo '<pre>$responses_by_question';
      // print_r($responses_by_question);
      // echo '</pre>';
      //
      // echo 'Count is: ' . count( $responses_by_question );

      foreach ($responses_by_question as $question_id => $responses) {


        $array_keys = ($live_open_responses_by_question[$question_id] != '') ? array_keys( $live_open_responses_by_question[$question_id] ) : array();

        if ($live_open_responses_by_question[$question_id] ) {

                  // echo '<pre>$array_keys';
                  // print_r($array_keys);
                  // echo '</pre>';

            foreach ($live_open_responses_by_question[$question_id] as $respondent_id => $response) {
              // echo '$respondent_id: ' . $respondent_id . '<br />';
              // echo '<pre>';
              // print_r($array_keys);
              // echo '</pre>';

              if( in_array( $response_id, $array_keys ) ) {
                // unset( $responses_by_question[$question_id][$respondent_id] );
                $responses_by_question[$question_id][$respondent_id] -> gigelelel = 'sugiuc';
                //Unset the parent value, if no more children
                // if ( empty( $responses_by_question[$question_id] ) ) {
                //   unset( $responses_by_question[$question_id] );
                // }
              }

            }
          }
      }


      // echo '<pre>';
      // print_r($live_open_responses_by_question);
      // echo '</pre>';
      // echo '<pre>$responses_by_question2';
      // print_r($responses_by_question);
      // echo '</pre>';
      //
      // echo 'Count is: ' . count( $responses_by_question );

      $return = '';
      $return .= 'Please check from the available open ended responses the ones you want featured with each questions on the front end:<br /><br />';

      // Leave this here, it'll be read by js
      $return .= '<div id="dm-post-id" post-id="' . $post_id . '"></div>'; // TODO Nu stiu la ce foloseste
      $return .= '<h4>Below is the list of the open ended questions available for ' . get_the_title() . '.</h4>';

      foreach ($global_open_ended_question_list as $q_number => $question) {
        $q_real_number = $question -> q_number;
        $return .= '<div class="dm-open-ended-question" question-number="' . $q_real_number. '">';

        $return .= '<table>
                      <tr>
                        <th class="dm-question-number">' . $q_number . '</th>
                        <th class="dm-response-text">' . $question -> q_short_text . '</th>
                        <th class="dm-graduation-year">Graduation year</th>
                        <th class="dm-feature-checkbox">Feature</th>
                      </tr>';

      if ($responses_by_question[$q_number]) {
        foreach ($responses_by_question[$q_number] as $key => $response) {

            $response_id      = $response -> id;
            $q_all_number     = $response -> q_all_number;
            $respondent_id    = $response -> respondent_id;
            $response_other   = $response -> response_other;
            $graduation_year  = $response -> graduation_year;

            if ($selected_open_ended_questions[$q_real_number][$response_id]) {
              // echo 'Sughibughi, chiar merge in pulamea';
              $checked = ' checked';
            } else {
              $checked = '';
            }


            $return .= '<tr class="dm-data-holder" response-id="' . $response_id . '" respondent-id="' . $respondent_id . '" graduation-year="' . $graduation_year . '" q_all_number="' . $q_all_number . '">
                          <td class="dm-question-number"></td>
                          <td class="dm-response-text">' . $response_other . '</td>
                          <td class="dm-graduation-year">' . $graduation_year . '</td>
                          <td class="dm-feature-checkbox">
                                <input type="checkbox" class="hiddenfornow" name="vehicle" value="Checked" ' . $checked . '>
                                <span class="feature-it">Feature it!</span>
                          </td>
                        </tr>';


        }
      }

        $return .= '</table>';

        $return .= '</div>';

      }

      return $return;


      foreach ($responses_by_question as $key_question => $question) {

          $q_number = $key_question;

      }

    }


} // End Class
?>
