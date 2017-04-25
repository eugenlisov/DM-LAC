<?php
/**
 * All Deprecated function go here.
 */

 /**
  * TODO Deprecated
  * NOTE See it in DM_MetaBoxes
  */

 function dm_generate_featured_answers_meta_block( $post_id = '' ) {
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



 /*
 * Use like this: $open_ended_question_list = get_open_ended_questions_list();
 * TODO Deprecated
 * NOTE find on DM_Question
 */
 function get_open_ended_questions_list() {

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





 // TODO Deprecated
 // NOTE Find it in DM_chart
 function dm_get_charts_data($iped) {
 	// $iped = $_POST['data'];

 	$question_options_list 								= get_question_options_list();
 	$responses_count_by_question_and_iped = get_response_count_by_question_and_iped( $iped );
 	$responses_by_iped										= get_response_count_by_iped( $iped );
 	// $responses_max      									= get_responses_max_score();
 	// $responses_min       									= get_responses_min_score();
 	// print_r($responses_max);
 	// print_r($responses_min);

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

 	return $question_options_list;

 	// print_r( $question_options_list );
 	die(json_encode( $question_options_list ));



 	// echo 'IPED IS';
 	print_r( $question_options_list );
 	// print_r( $responses_count_by_question_and_iped );

 }






 // TODO Deprecated
 // NOTE Inlocuita in DM_School cu get_iped
 function get_school_iped() {
   $current_college_id = get_the_ID();

   $current_college_iped = get_post_meta( $current_college_id, 'school_iped', true );

   return $current_college_iped;
 }

 // TODO Deprecated
 // NOTE Inlocuita in DM_School cu get_contact_data
 function get_school_contact_data ( $school_id = '' ) {

   if (!$school_id) $school_id = get_the_ID();


   // 1. Get the highriseID from this school
   $current_highrise_id = get_post_meta($school_id, 'school_highrise_id', true);

   // 2. Then just grab the row from the dm_school_contacts table

   global $wpdb;

   $sql_school_contact_data = 'SELECT * from dm_school_contacts WHERE highrise_id = ' . $current_highrise_id;

   $school_contact_data = (array) $wpdb->get_row( $sql_school_contact_data );

   // echo '<pre>';
   // print_r($school_contact_data);
   // echo '</pre>';

   return $school_contact_data;

 }

 /*
 * TODO Deprecated;
 * NOT Now used individually inside the DM_Tab to call the list of questions of the current tab - used as 'get_tab_questions()';
 */
 function get_tabs_questions() {

   global $wpdb;

   // Get the data from the 'dm_survey_responses' table
   $sql_tabs_questions_list = 'SELECT * FROM `dm_survey_tab_questions`
                               ORDER BY
                               tab_id ASC, question_id ASC';
   $tabs_questions_list_raw = $wpdb->get_results( $sql_tabs_questions_list );

   $tabs_questions_list = array();

   foreach ($tabs_questions_list_raw as $key => $value) {
     $tabs_questions_list[$value -> tab_id][] = $value -> question_id;
   }

   return $tabs_questions_list;

   // echo '<pre>';
   // print_r($tabs_questions_list);
   // echo '</pre>';

 }


 /*
 * TODO Deprecated
 * NOTE Moved to DM_Tabs
 */
 function get_tabs_list() {

   global $wpdb;

   // Get the data from the 'dm_survey_responses' table
   // $sql_tabs_list = 'SELECT * FROM `dm_survey_tabs` ORDER BY type DESC';
   $sql_tabs_list = 'SELECT * FROM `dm_survey_tabs`';
   $tabs_list = $wpdb->get_results( $sql_tabs_list );

   return $tabs_list;

   // echo '<pre>';
   // print_r($tabs_list);
   // echo '</pre>';

 }


 /*
 * TODO Deprecated
 * NOTE: See this function identical in DM_Questions
 */
 function get_all_questions_list() {

   global $wpdb;

   // Get the data from the 'dm_survey_responses' table
   $sql_all_questions_list = 'SELECT * FROM `dm_survey_all_questions` WHERE NOT other = "yes"';
   $all_questions_list = $wpdb->get_results( $sql_all_questions_list );

   $all_questions_list_by_id = array();
   foreach ($all_questions_list as $key => $question) {
     $all_questions_list_by_id[$question -> id] = $question;
   }

   return $all_questions_list_by_id;
   // return $all_questions_list;

   // echo '<pre>';
   // print_r($all_questions_list);
   // echo '</pre>';

 }



 // Hooks, filters, etc


 // TODO Deprecated
 // NOTE Inlocuita in DM_Template elements
 function theme_slug_filter_the_content( $content ) {

   $user_id = get_current_user_id();
   $school_id = get_the_ID();
   $user_note = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];
   $rating 	= get_user_meta( $user_id, 'dm_user_ratings', true )[$school_id];

   // $contact_data  = get_field( 'school_contact_data' )[0];
   $contact_data  = get_school_contact_data ( $school_id );

   // echo '<pre>';
   // print_r($contact_data);
   // echo '</pre>';


   if ($rating) {
     $rating_string = '<div>Your rating:';
     $rating_string .= '<span class="dm-rating">';
     for ($i=1; $i <= $rating; $i++) {
       $rating_string .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
     }
     $rating_string .= '</span></div>';
   } else {
     $rating_string = '';
   }

     $logged_in_content = '<div class="row dm-college-top-content logged-in">
                             <div class="col-md-3"><button type="button" class="btn btn-primary btn-contact-school trigger-contact-school-modal"><i class="fa fa-phone" aria-hidden="true"></i> Contact school</button></div>
                             <div class="col-md-4">' . do_shortcode('[favorite_button]') . '</div>
                             <div class="col-md-5">
                                 ' . $rating_string . '
                                 <div class="row">
                                   <div class="col-md-1"><i class="fa fa-sticky-note" aria-hidden="true"></i></div>
                                   <div class="col-md-10 user-note">' . $user_note . '</div>
                                 </div>
                                 <div class="row edit-user-note">
                                   <small class="dm-edit-note-rating trigger-login-upgrade-modal">Edit note & rating <i class="fa fa-pencil" aria-hidden="true"></i> </small>
                                   <small><a href="' . get_permalink( PAGE_MY_COLLEGES ) . '">See all your colleges <i class="fa fa-university" aria-hidden="true"></i></a></small>
                                 </div>
                               </div>
                           </div>';

     $logged_out_content = '<div class="dm-college-top-content logged-out">
                             <div class="col-md-3"><button type="button" class="btn btn-primary btn-contact-school trigger-contact-school-modal"><i class="fa fa-phone" aria-hidden="true"></i> Contact school</button></div>
                             <div class="col-md-9">
                               <button class="dm-add-favorite-logged-out trigger-login-upgrade-modal">Add to My Colleges<i class="sf-icon-star-empty"></i></button>
                             </div>
                           </div>';


     $logged_out_modal = '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-login-upgrade-modal">
     	<div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title" id="mySmallModalLabel">Log in or Upgrade</h4>
         </div>
         <div class="modal-body">
           <p>To make use of this feature, you need to log in first.</p>
     			<p> <a href="' . get_permalink( LOG_IN_PAGE ) . '" class="btn btn-default">Log In</a> <a href="' . get_permalink( PAGE_GET_PREMIUM ) . '?rid=pv47iM" class="btn btn-default">Get Premium</a></p>
     		</div>
       </div>
     </div>

     </div>';


     $contact_school_modal = '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-contact-school-modal">
     	<div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title" id="mySmallModalLabel">Contact ' . get_the_title() . '</h4>
         </div>
         <div class="modal-body">
           <p>Here\'s all the info you need to get in touch with this school.</p>

           <table style="width:100%">';

     foreach ($contact_data as $key => $contact_item) {
       switch ($key) {
         case 'full_name':
           $contact_label = 'Contact Representative';
           break;
         case 'title':
           $contact_label = 'Title';
           break;
         case 'phone':
           $contact_label = 'Phone';
           break;
         case 'email':
           $contact_label = 'Email';
           break;
         default:
           $contact_label = '';
           break;
       }

         if ($contact_label != '' && trim($contact_item) != '' ) {
           $contact_school_modal .= '
                   <tr>
                     <td class="contact-label"><strong>' . $contact_label . ': </strong></td>
                     <td class="contact-item">' . $contact_item . '</td>
                   </tr>';
         }
     }


     $contact_school_modal .= '
           </table>
     		</div>
       </div>
     </div>

     </div>';



     // $user_id = get_current_user_id();
     // $saved_user_notes = get_user_meta( $user_id, 'dm_user_notes', true );

     $ratings_block = '<span class="dm-rating">';
     for ($i=1; $i < 6; $i++) {
       $ratings_block .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
     }

     $ratings_block .= '</span>';

     $logged_in_modal = '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-add-note-rating-modal">
     	<div class="modal-dialog modal-md" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title" id="mySmallModalLabel">Add a note about ' . get_the_title() . '</h4>
         </div>
         <div class="modal-body">

     			<p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
     			<p><button id="dm-single-note-button" type="button" class="btn btn-primary">Save Note</button></p>
     			<p><strong>Your rating: </strong>
     				' . $ratings_block . '
     			</p>
     			<p><small>(So you later know what you thought of this)</small></p>
     		</div>
       </div>
     </div>

     </div>';

     if (is_singular( 'schools' )) {

       if (is_user_logged_in()) {
         $custom_content = $logged_in_content . $logged_in_modal . $contact_school_modal;
       } else {
         $custom_content = $logged_out_content . $logged_out_modal . $contact_school_modal;
       }

     }

     $custom_content .= $content;
     return $custom_content;
 }
 // TODO Deprecated
 // add_filter( 'the_content', 'theme_slug_filter_the_content' );


 ?>
