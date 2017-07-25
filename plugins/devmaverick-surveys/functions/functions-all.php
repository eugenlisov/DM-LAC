<?php

/**
 * Function that prevents redirection to the error page when a user can't access a protected school.
 * Protected tabs will continue to be hidden though.
 */
function dm_by_protected_school_Redirect($data) {
    return true;
}

add_filter('mm_bypass_content_protection', 'dm_by_protected_school_Redirect');



/*
* Using the IPED, it counts the distinct instances of the respondent_id for each IPED.
*/
function get_response_count_by_iped( $iped = '' ) {
  if ($iped == '') return 0;

  global $wpdb;

  // Get the data from the 'dm_survey_responses' table
  $sql_response_count = 'SELECT COUNT(DISTINCT(respondent_id)) AS count FROM `dm_survey_responses` WHERE iped = ' . $iped;
  $response_count = $wpdb->get_row( $sql_response_count );
  $response_count = $response_count -> count;

  return $response_count;

  // echo '<pre>';
  // print_r($response_count);
  // echo '</pre>';



}










function dm_generate_featured_answers_front_end( $post_id = '', $q_all_number = '' ) {
  if (!$post_id) return false;

  global $wpdb;



   $sql_response = 'SELECT * from dm_open_ended_questions
                            WHERE post_id = ' . $post_id . ' ORDER BY q_all_number ASC';

                                // echo $sql_response;

   $result = $wpdb->get_results( $sql_response );
  //  print_r($result);

   $answers_by_q_all_number = [];
   $q_all_array = [];

   foreach ($result as $key => $answer) {
     $q_all_number = $answer -> q_all_number;
     $q_all_array[] = $q_all_number;
     $answers_by_q_all_number[$q_all_number][] = $answer;
   }

   $q_all_array = array_unique($q_all_array);
   $q_all_string = implode (", ", $q_all_array);

   $sql_question_names = 'SELECT id, q_short_text FROM dm_survey_all_questions WHERE id IN (' . $q_all_string . ')';

   $question_names = $wpdb->get_results( $sql_question_names );
   $question_names_by_id = [];
   foreach ($question_names as $key => $value) {
     $question_names_by_id[$value -> id] = $value -> q_short_text;
   }

  //  echo '<pre>';
  //  print_r($question_names_by_id);
  //  echo '</pre>';

   $return_string = '';

   foreach ($answers_by_q_all_number as $q_all_number => $question) {

     $return_string .= '


     <div class="dm-open-ended-question" question-number=" ' . $q_all_number . '">
      <table>
        <tbody>
          <tr>
            <th class="dm-question-number">' . $q_all_number . '</th>
            <th class="dm-response-text">' . $question_names_by_id[$q_all_number] . '</th>
            <th class="dm-graduation-year">Graduation year</th>
            <th class="dm-feature-checkbox">Action</th>
          </tr>';

          foreach ($question as $key_2 => $response) {
            $response_id      = $response -> id;
            $respondent_id    = $response -> respondent_id;
            $graduation_year  = $response -> graduation_year;
            $open_response    = $response -> open_response;
            if ($graduation_year == "0") {
              $graduation_year = "";
            }
            if ($open_response == "0" || $open_response == "") continue;

            $return_string .=   '
                  <tr class="dm-data-holder" response-id="' . $response_id . '" respondent-id="' . $respondent_id. '" graduation-year="2016" q_all_number="15">
                    <td class="dm-question-number"></td>
                    <td class="dm-response-text">' . stripslashes  ($open_response) . '</td>
                    <td class="dm-graduation-year">' . $graduation_year. '</td>
                    <td class="dm-feature-checkbox"> <span class="remove-it">Remove!</span></td>
                  </tr>';
          }


    $return_string .= '

        </tbody>
      </table>
    </div>';
   }
   return $return_string;

}


function get_question_options_list() {

  global $wpdb;

  // Get the data from the 'dm_survey_responses' table
  $sql_options_list= 'SELECT q_id_all, option_text, option_score FROM `dm_survey_options` GROUP BY q_id_all, option_score';
  $options_list = $wpdb->get_results( $sql_options_list );
  // $response_count = $response_count -> count;

  $options_list_by_id = array();
  foreach ($options_list as $key => $value) {
    $options_list_by_id[$value -> q_id_all][$value -> option_score] = $value;
  }

  return $options_list_by_id;

  echo '<pre>Vasile';
  print_r($options_list_by_id);
  echo '</pre>';



}



function get_response_averages_by_iped( $iped = '' ) {
  if ($iped == '') return 0;

  global $wpdb;

  // New way, grab data from the averages table

  $sql_response_average_by_iped = 'SELECT q_all_number, school_ave FROM dm_school_averages WHERE iped = ' . $iped;


  // Old way

  // Get the data from the 'dm_survey_responses' table
  // $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(q_score), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
  // $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(CASE WHEN q_score <> 0 THEN q_score ELSE NULL END), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
  $response_averages = $wpdb->get_results( $sql_response_average_by_iped );
  // $response_averages = $response_averages -> count;

  $response_averages_by_id = array();
  foreach ($response_averages as $key => $average) {
    $response_averages_by_id[$average -> q_all_number] = $average;
  }

  // echo '<pre>';
  // print_r($response_averages_by_id);
  // echo '</pre>';

  return $response_averages_by_id;





}

/**
 * Calculate Averages by IPED. For each IPED, calculated avg and save it to a table to be used on the front end.
 * Also saves the timestamp, so we know whether the average is old or not. TODO
 * The script wil ALWAYS delete whatever is already in the dm_school_averages table before writing new stuff
 * @param  string $iped [description]
 * @return [type]       [description]
 */
function calculate_averages_by_iped( $iped = '' ) {
  if ($iped == '') return 0;

  global $wpdb;

  // Get the data from the 'dm_survey_responses' table
  $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(CASE WHEN q_score <> 0 THEN q_score ELSE NULL END), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
  $response_averages = $wpdb->get_results( $sql_response_averages );
  // $response_averages = $response_averages -> count;

  $response_averages_by_id = array();
  foreach ($response_averages as $key => $average) {
    $response_averages_by_id[$average -> q_all_number] = $average;
  }

  // $sql_questions = 'SELECT DISTINCT(q_number), id as q_all_number FROM dm_survey_all_questions';
  // $questions = $wpdb->get_results( $sql_questions );
  //
  // echo '<pre>';
  // print_r($questions);
  // echo '</pre>-----------';
  //
  //
  //

  $final_array = array();

  foreach ($response_averages_by_id as $key => $value) {
    $current_average = $value -> average;
    $q_all_number = $value -> q_all_number;

    if ($current_average != '') {
      $string = '(' . $iped . ', ' . $q_all_number . ', ' . $current_average . ')';
      $final_array[] = $string;
      // echo $string . ' <br />';
    }

  }

  // echo '<pre>';
  // print_r($final_array);
  // echo '</pre>';

  $final_insert_string = implode(", ", $final_array);

  delete_averages_by_iped( $iped );


  $sql_query = 'INSERT INTO dm_school_averages (iped, q_all_number, school_ave) VALUES ' . $final_insert_string;
  $result = $wpdb->query($sql_query);
  if (!$result) {
    // echo 'Error deleting for ' . $iped . '<br />';
  } else {
    // echo 'Successfully Deleted for IPED ' . $iped . '<br />';
  }

  if (!$result) {
    // echo 'ERROR! for ' . $iped . '<br />';
  } else {
    // echo 'Successfully added the averages for IPED ' . $iped . '<br />';
  }

  // Always recalculate the LAC averages

  calculate_lac_average();

  return $response_averages_by_id;



}

function delete_averages_by_iped( $iped = '') {
  if (!$iped) return false;

  global $wpdb;
  $sql_delete = 'DELETE FROM dm_school_averages WHERE iped = ' . $iped;
  $result = $wpdb->query($sql_delete);
}




/**
 * Calculate LAC Averages by IPED. If takes all questions in the table and groups them by q_all_number
 * Then saves the data to the dm_lac_averages
 * The script wil ALWAYS delete whatever is already in the dm_lac_averages table before writing new stuff
 * @param  string $iped [description]
 * @return [type]       [description]
 */
function calculate_lac_average() {

  global $wpdb;

  // Get the data from the 'dm_survey_responses' table
  $sql_lac_averages = 'SELECT q_all_number, ROUND( AVG(CASE WHEN school_ave <> 0 THEN school_ave ELSE NULL END), 2) as average FROM `dm_school_averages` GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
  $lac_averages = $wpdb->get_results( $sql_lac_averages );

  $response_averages_by_id = array();
  foreach ($lac_averages as $key => $average) {
    $response_averages_by_id[$average -> q_all_number] = $average;
  }


  $final_array = array();

  foreach ($response_averages_by_id as $key => $value) {
    $current_average = $value -> average;
    $q_all_number = $value -> q_all_number;

    if ($current_average != '') {
      $string = '(' . $q_all_number . ', ' . $current_average . ')';
      $final_array[] = $string;
    }

  }

  // echo '<pre>';
  // print_r($final_array);
  // echo '</pre>';

  $final_insert_string = implode(", ", $final_array);

  // 1. Delete everything that's in the average table, so we have a clean slate
  $sql_delete_query = 'DELETE FROM dm_lac_average';
  $result = $wpdb->query($sql_delete_query);


  // 2. Insert the new LAC Averages
  $sql_query = 'INSERT INTO dm_lac_average (q_all_number, average) VALUES ' . $final_insert_string;
  $result = $wpdb->query($sql_query);
  if (!$result) {
    // echo 'Error inserting ALC averages<br />';
  } else {
    // echo 'Successfully inserted LAC Averages<br />';
  }

}


//Get lac Averages
// TODO Deprecate this after comparisons tab is working
//
// Actually, updat this function to only use the dm_lac_average table
// Use like this: $lac_averages = get_response_averages();

function get_response_averages() { // TODO Rename to get_lac_averages

  global $wpdb;

  // 1. Get the data from the 'dm_lac_average' table
  $sql_response_averages = 'SELECT * from dm_lac_average';
  $response_averages = $wpdb->get_results( $sql_response_averages );


  $response_averages_by_id = array();
  foreach ($response_averages as $key => $average) {
    $response_averages_by_id[$average -> q_all_number] = $average;
  }

  // echo '<pre>';
  // print_r($response_averages_by_id);
  // echo '</pre>';

  return $response_averages_by_id;
}


//Get max scores

function get_responses_max_score() {

  global $wpdb;

  // Get the data from the 'dm_survey_options' table
  $sql_responses_max = 'SELECT `q_id_all`, max(`option_score`) as max FROM `dm_survey_options` GROUP BY q_id_all ORDER BY q_id_all ASC';
  $responses_max = $wpdb->get_results( $sql_responses_max );
  // $response_averages = $response_averages -> count;

  $responses_max_by_id = array();
  foreach ($responses_max as $key => $max) {
    $responses_max_by_id[$max -> q_id_all] = $max;
  }

  return $responses_max_by_id;

  // echo '<pre>';
  // print_r($response_averages_by_id);
  // echo '</pre>';



}

//Get max scores

function get_responses_min_score() {

  global $wpdb;

  // Get the data from the 'dm_survey_options' table
  $sql_responses_min = 'SELECT `q_id_all`, min(`option_score`) as min FROM `dm_survey_options` GROUP BY q_id_all ORDER BY q_id_all ASC';
  $responses_min = $wpdb->get_results( $sql_responses_min );
  // $response_averages = $response_averages -> count;

  $responses_min_by_id = array();
  foreach ($responses_min as $key => $min) {
    $responses_min_by_id[$min -> q_id_all] = $min;
  }

  return $responses_min_by_id;

  // echo '<pre>';
  // print_r($response_averages_by_id);
  // echo '</pre>';



}

// Get count for each option, for each question, by iped.

function get_response_count_by_question_and_iped( $iped = '' ) {
  if ($iped == '') return 0;

  global $wpdb;

  // Get the data from the 'dm_survey_responses' table
  // $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(q_score), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
  $sql_responses_count = 'SELECT q_all_number, q_score, COUNT( q_score) AS count FROM `dm_survey_responses`  WHERE `iped` = ' . $iped . ' GROUP BY q_all_number, q_score ORDER BY q_all_number ASC';
  $responses_count = $wpdb->get_results( $sql_responses_count );
  // $response_averages = $response_averages -> count;

  $responses_count_by_question_and_iped = array();
  foreach ($responses_count as $key => $value) {
    $responses_count_by_question_and_iped[$value -> q_all_number][$value -> q_score] = $value -> count;
  }

  return $responses_count_by_question_and_iped;

  // echo '<pre>';
  // print_r($responses_count_by_question_and_iped);
  // echo '</pre>';



}








/**
 * Get an associative array of School names, where the key is the IPED
 * @return [associative array] [key is the iped]
 */
function get_unique_schools_by_ipeds() {
  global $wpdb;

  $sql_unique_ipeds = 'SELECT * from dm_school_ipeds';
  $unique_ipeds = $wpdb->get_results( $sql_unique_ipeds );
  $unique_ipeds_by_ipeds = array();

  foreach ($unique_ipeds as $key => $school) {
    $iped = $school -> iped;
    $unique_ipeds_by_ipeds[$iped] = $school -> school_name;
  }
  return $unique_ipeds_by_ipeds;

  // echo '<pre>';
  // print_r($unique_ipeds);
  // echo '</pre>';
}

/**
 * Get an array of all the IPEDs that have been associated to college posts.
 * Check the wp_postmeta table
 * @return [array] [key is the iped]
 * Use like this $associated_ipeds = get_associated_ipeds();
 */
function get_associated_ipeds() {
  global $wpdb;

  $sql_associated_ipeds = 'SELECT post_id FROM `wp_postmeta` WHERE `meta_key` = "school_iped" AND `meta_value` <> ""';
  $associated_ipeds = $wpdb->get_results( $sql_associated_ipeds );
  $associated_ipeds_array = array();

  foreach ($associated_ipeds as $key => $post_id_obj) {
    $associated_ipeds_array[] = $post_id_obj -> post_id;
  }
  return $associated_ipeds_array;

  // echo '<pre>';
  // print_r($associated_ipeds_array);
  // echo '</pre>';
}

function get_unique_contact_data() { // TODO Change name to somethign more appropriate. It only returns the highrise id and school name
  global $wpdb;

  $sql_unique_contact_data = 'SELECT * from dm_school_contacts';
  $unique_contact_data = $wpdb->get_results( $sql_unique_contact_data );
  $unique_contact_data_by_id = array();

  foreach ($unique_contact_data as $key => $contact) {
    $highrise_id = $contact -> highrise_id;
    $unique_contact_data_by_id[$highrise_id] = $contact -> school_name;
  }
  return $unique_contact_data_by_id;

  // echo '<pre>';
  // print_r($unique_contact_data_by_id);
  // echo '</pre>';
}




/*
* Get open ended responses by school IPED and question
* Use like this: $responses_by_question = get_open_eded_responses_by_iped_and_question( $iped );
*/

/**
 * [get_open_eded_responses_by_iped_and_question description]
 * @param  [type] $iped [description]
 * @return [type]       [description]
 */
function get_open_eded_responses_by_iped_and_question( $iped ) {

  if (!$iped) return false;

  // Get the data from the 'dm_survey_responses' table
  $sql_all_open_ended_responses_list = 'SELECT r.id, r.q_all_number, r.respondent_id, r.response_other
  	FROM dm_survey_all_questions q, dm_survey_responses r
      WHERE q.id = r.q_all_number AND q.other = "yes" AND r.iped = ' . $iped . ' AND `response_other` <> ""
      ORDER BY respondent_id ASC';


  global $wpdb;
  $open_ended_responses = $wpdb->get_results( $sql_all_open_ended_responses_list );

  $responses_by_question = array();
  $respondent_id_array = array();
  foreach ($open_ended_responses as $key => $response) {
    $responses_by_question[$response -> q_all_number][] = $response;

    if (!in_array($response -> respondent_id, $respondent_id_array)) {
      $respondent_id_array[] = $response -> respondent_id;
    }
  }

  // Now get the extra info required ('Answesrs given, graduation year')

  // echo '<pre>';
  // print_r($respondent_id_array);
  // echo '</pre>';
  $respondent_id_string = implode(",", $respondent_id_array);
  // echo $respondent_id_string;

  $sql_graduation_year = 'SELECT respondent_id, response as graduation_year
  	FROM dm_survey_responses
      WHERE q_all_number = 53 AND `respondent_id` IN (' . $respondent_id_string . ')
      ORDER BY respondent_id ASC';
  $graduation_years = $wpdb->get_results( $sql_graduation_year );
  $graduation_years_array = array();

  foreach ($graduation_years as $key => $graduation_year) {
    $graduation_years_array[$graduation_year -> respondent_id] = $graduation_year -> graduation_year;
  }

  // Now save the year to the original array

  foreach ($responses_by_question as $key_question => $question) {

      foreach ($question as $key_response => $response) {
        $respondent_id = $response -> respondent_id;
        $responses_by_question[$key_question][$key_response] -> graduation_year = $graduation_years_array[$respondent_id];
      }
  }

  // echo '<pre>';
  // print_r($graduation_years_array);
  // echo '</pre>';

  // echo '<pre>';
  // print_r($responses_by_question);
  // echo '</pre>';
  return $responses_by_question;


}

/*
* GET Charts data
* Use like this: $responses_by_question = get_open_eded_responses_by_iped_and_question( $iped );
* Return array {
*               question_all_id {
*                               quesition_name (optional)
*                               iped_percent
*                               lac_percent
*                               iped_response
*                               lac_response
*                               }
*               }
*/





/*
* GET Charts data for the comparisons tab
* Use like this: $comparisons_chart_data = dm_get_comparisons_charts_data( $iped );
* Return array {
*               question_all_id {
*                               quesition_name (optional)
*                               iped_percent
*                               lac_percent
*                               iped_response
*                               lac_response
*                               }
*               }
*/
function dm_get_comparisons_charts_data($iped) {

  $school_averages = get_response_averages_by_iped( $iped );
  $lac_averages = get_response_averages();
  $responses_max      									= get_responses_max_score();
  $responses_min       									= get_responses_min_score();
  $all_questions = get_all_questions_list();

  $tabs_list           = get_tabs_list(); // OK


  // echo '<pre class="col-md-6">';
  // print_r($tabs_list);
  // echo '</pre>';
  // echo '<pre class="col-md-6">';
  // print_r($lac_averages);
  // echo '</pre>';

  $comparisons_charts_data_return = array();

	$question_options_list 								= get_question_options_list();
	$responses_count_by_question_and_iped = get_response_count_by_question_and_iped( $iped );
	$responses_by_iped										= get_response_count_by_iped( $iped );
	$responses_max      									= get_responses_max_score();
	$responses_min       									= get_responses_min_score();

  // echo '<pre>';
  // // print_r($responses_max);
	// // print_r($responses_min);
	// print_r($responses_by_iped);
  // echo '</pre>';

	//Once we have the two above, we merge them into a larger array;
	$question_options_count_array = array();
	$max_percent_key = 0;

	foreach ($question_options_list as $key_question => $question) {
		foreach ($question as $key_option => $option) {
			// print_r($responses_count_by_question_and_iped[$key_question][$key_option]);
			// $question_options_list[$key_question][$key_option] -> count = $responses_count_by_question_and_iped[$key_question][$key_option];

			if ($responses_count_by_question_and_iped[$key_question][$key_option]) {
				$count = $responses_count_by_question_and_iped[$key_question][$key_option];
				$percent = round( $count / $responses_by_iped * 100 );

				$question_options_list[$key_question][$key_option] -> count = $count;
				$question_options_list[$key_question][$key_option] -> percent = $percent;

				$max_percent_key = ($percent > $max_percent) ? $key_option : $max_percent_key;
			} else {
				$question_options_list[$key_question][$key_option] -> count = 0;
				$question_options_list[$key_question][$key_option] -> percent = 0;
			}

		}
	}

  // echo '<pre>';
  // print_r($question_options_list);
  // echo '</pre>';

	return $question_options_list;

	// print_r( $question_options_list );
	die(json_encode( $question_options_list ));



	// echo 'IPED IS';
	print_r( $question_options_list );
	// print_r( $responses_count_by_question_and_iped );

}

/*
* Get open ended responses by school IPED and question
* Use like this: $responses_by_question = get_open_eded_responses_by_iped_and_question( $iped );
*/
function get_open_ended_responses_by_ids( $response_id_array ) {

  if (!$response_id_array) return false;


  $response_id_string = implode(",", $response_id_array);




  // Get the data from the 'dm_survey_responses' table
  $sql_open_ended_responses = 'SELECT * FROM dm_survey_responses
      WHERE id IN (' . $response_id_string . ')';

      echo 'SQL: ' . $sql_open_ended_responses;

  global $wpdb;
  $open_ended_responses = $wpdb->get_results( $sql_open_ended_responses );






  $respondent_id_array = array();
  foreach ($open_ended_responses as $key => $response) {

    if (!in_array($response -> respondent_id, $respondent_id_array)) {
      $respondent_id_array[] = $response -> respondent_id;
    }
  }

  // Now get the extra info required ('Answesrs given, graduation year')

  // echo '<pre>';
  // print_r($respondent_id_array);
  // echo '</pre>';
  $respondent_id_string = implode(",", $respondent_id_array);
  // echo $respondent_id_string;

  $sql_graduation_year = 'SELECT respondent_id, response as graduation_year
  	FROM dm_survey_responses
      WHERE q_all_number = 53 AND `respondent_id` IN (' . $respondent_id_string . ')
      ORDER BY respondent_id ASC';
  $graduation_years = $wpdb->get_results( $sql_graduation_year );
  $graduation_years_array = array();

  foreach ($graduation_years as $key => $graduation_year) {
    $graduation_years_array[$graduation_year -> respondent_id] = $graduation_year -> graduation_year;
  }

  // Now save the year to the original array


      foreach ($open_ended_responses as $key_response => $response) {
        $respondent_id = $response -> respondent_id;
        $open_ended_responses[$key_response] -> graduation_year = $graduation_years_array[$respondent_id];
      }


  // echo '<pre>';
  // print_r($open_ended_responses);
  // echo '</pre>';

  return $open_ended_responses;


}
















/////////////////////////////////// OLD STUFF FROM GOFLEET OR WHATEVER /////////////////////////

// Setup the ajaxurl and other addresses in the headr, so that we have clean JS files.


add_action('wp_head','dm_enqueue_ajax_addresses');
function dm_enqueue_ajax_addresses() {
  $dm_customer_second_step_id = esc_attr( get_option( 'dm_customer_second_step_id' ) );
  $dm_customer_third_step_id = esc_attr( get_option( 'dm_customer_third_step_id' ) );
  $dm_customer_quote_generated_id = esc_attr( get_option( 'dm_customer_quote_generated_id' ) );
  ?>
  <script type="text/javascript">
  var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
  </script>
  <?php
}








function dm_is_descendant_of( $ancestor_id = '' ) {
  if ( ! $ancestor_id ) return false;

  $post_id = get_the_ID();
  $post = get_post( $post_id );

  // First let's do the first level
  if ( $post -> post_parent == '' ) return false;
  if ( $post -> post_parent == $ancestor_id ) return true;

  // First let's do the second level
  $post_id = $post -> post_parent;
  $post = get_post( $post_id );

  // First let's do the first level
  if ( $post -> post_parent == '' ) return false;
  if ( $post -> post_parent == $ancestor_id ) return true;

  // No need for third level

}



function dm_add_protected_overlay( $content ) {

  if ( is_page() ) {


    $is_rankings_descendant = dm_is_descendant_of( PAGE_SCHOOL_RANKINGS );
    if ( ! $is_rankings_descendant ) {
      return $content;
    }


    //If it's a descendant, add the CSS

    $css = '<style>
            .tablepress tbody {
              -webkit-filter: blur(12px);
              filter: blur(10px);
            }
            </style>';


    $js = '<script>
            jQuery( document ).ready(function($) {
              var overlayContent = \'<div class="dm-protected-overlay-content"> <h3>Premium Member Only Content</h3> <br /> <span>Upgrade Now</span></div>\';
              var overlay = \'<a href="' . PAGE_GET_PREMIUM_PERMALINK . '\" class="dm-no-click-overlay">\' + overlayContent + \'</a>\';
              $( ".tablepress" ).wrap( \'<div class="dm-tablepres-protected-wrapper"></div>\' );
              $( "body" ).find( ".dm-tablepres-protected-wrapper" ).prepend( overlay );
            });
            </script>';


    $allowed_shortcode  = "[MM_Access_Decision access='true'][/MM_Access_Decision]";
    $denied_shortcode   = "[MM_Access_Decision access='false']" . $css . $js . "[/MM_Access_Decision]";

    $protection_css_js .= do_shortcode( $allowed_shortcode );
    $protection_css_js .= do_shortcode( $denied_shortcode );

    $content = $protection_css_js . $content;

  }
  return $content;
}


add_filter( 'the_content', 'dm_add_protected_overlay', 10, 10 );





// NOTE Deprecated ////////////////////////////////////



 ?>
