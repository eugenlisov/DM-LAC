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
 TODO Deprecated
 NOTE Moved to class DM_Tabs
 * Create functions for all shortcodes
 */

 function dm_school_tabs() {
 	// wp_enqueue_style('dm-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
   wp_enqueue_style('dm-font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
 	wp_enqueue_style('dm-tabs', plugins_url() . '/devmaverick-surveys/assets/css/tabs.css');

   wp_enqueue_script('dm-bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), '1.0.0', true );
 	wp_enqueue_script('dm-d3-script', 'http://d3js.org/d3.v3.min.js', array(), '1.0.0', true );

 	wp_enqueue_script('dm-tabs', plugins_url() . '/devmaverick-surveys/assets/js/tabs.js', array(), '1.0.0', true );




 	// echo 'TEsting smart tags';

 // 	echo do_shortcode("
 // [MM_Access_Decision access='true']
 //
 // TEST EUGEN You've had access to this page since [MM_Content_Data name='dateAvailable']. Click here to visit the page: <a href=\"[MM_Content_Link]\">[MM_Content_Data name='title']</a>
 //
 // [/MM_Access_Decision]
 //
 //
 // [MM_Access_Decision access='false']
 //
 // TEST EUGEN You don't have access to [MM_Content_Data name='title']. Click here to purchase access: <a href=\"[MM_Purchase_Link productId='1']\">Get Access</a>
 //
 // [/MM_Access_Decision]");

 	$iped                = get_school_iped();

 	// If there is not IPED associated with this school, just not display the Tabs.
 	if ($iped == '') return;



   $tabs_list           = get_tabs_list(); // OK
   $all_questions_list  = get_all_questions_list();
   $tabs_questions      = get_tabs_questions();
 	$response_averages   = get_response_averages(); // TODO. Trebuie refacut
   $response_averages_by_iped   = get_response_averages_by_iped( $iped ); // OK - functie refacuta
 	$lac_averages 			 = get_response_averages(); // To be used on the comparisons tab;

   $responses_max       = get_responses_max_score(); // TODO de verificat la ce foloseste  asta
   $responses_min       = get_responses_min_score(); // TODO de verificat la ce foloseste  asta


 	$charts_data 						= dm_get_charts_data( $iped );
 	$comparisons_chart_data = dm_get_comparisons_charts_data( $iped );


 	// Nu stiu daca e ce TRebuie
 	$responses_count_by_question_and_iped = get_response_count_by_question_and_iped( $iped ); //- Trebuie salvat aici numarul de raspunsuri pentru fiecare intrebare.
 	$responses_by_iped										= get_response_count_by_iped( $iped );

 	$question_options_list 								= get_question_options_list();

   // echo '<pre class="col-md-6">';
   // print_r($tabs_questions);
   // echo '</pre>';
 	// echo '<pre class="col-md-6">';
 	// print_r($lac_averages);
 	// echo '</pre>';

 	global $post;
 	$post_id =  $post->ID;


 	$selected_open_ended_questions = get_post_meta($post_id, 'dm_open_ended_questions', true);


 	?>

   <!-- This is where the code goes -->




 		<div class="dm-school-tabs bs-example" school-iped="<?php echo $iped; ?>">
 		    <ul class="nav nav-tabs">
             <?php
             foreach ($tabs_list as $key => $tab) {

               $tab_id             = $tab -> id;
               $tab_name           = $tab -> tab_name;
               $font_awesome_class = $tab -> font_awesome_class;

               $slug     =  strtolower( str_replace(" ", "-", $tab_name) );
               $active   = ( $key == 0 ) ? 'class="active"' : '';

               echo '<li ' . $active. '><a data-toggle="tab" href="#' . $slug . '"><i class="fa ' . $font_awesome_class . '" aria-hidden="true"></i>  ' . $tab_name . '</a></li>';
               // echo $slug . '<br />';
               # code...
             }
              ?>
 		    </ul>
 		    <div class="tab-content">

           <?php
           foreach ($tabs_list as $key => $tab) {

 						// echo '<pre>';
 						// print_r($tab);
 						// echo '</pre>';





             $tab_id             	= $tab -> id;
             $tab_name           	= $tab -> tab_name;
 						$tab_intro 						= $tab -> tab_intro;
 						$protected_narrative 	= $tab -> protected_narrative;
             $font_awesome_class 	= $tab -> font_awesome_class;

             $slug     =  strtolower( str_replace(" ", "-", $tab_name) );
             $active   = ( $key == 0 ) ? 'in active' : '';

             echo '<div id="' . $slug . '" class="tab-pane fade ' . $active . '">
 		            <h3>' . $tab_name . '</h3>';

 						$current_tab_content = '';

 						$current_tab_content .= '';

 						echo '<div class="tab-intro">' . $tab_intro . '</div>';

 						// If Overview tab, display it here
 						if ($key == 0) {
 							$overview_tab_content = get_field( "school_overview" );
 							echo $overview_tab_content;
 						}
 						if ($key == 4) { // The comparisons tab


 							$comparisons_tab_return = '';


 							$tab_questions_ids = $tabs_questions[5];
 							//Loop through all tab data again
 							// foreach ($tabs_questions as $key => $tab_questions_ids) { // Aici incepe loopul pentru intrebarea curenta

 								// $tab_name           = $tabs_list[$key - 1] -> tab_name;
 								// $tab_narrative			= $tabs_list[$key - 1] -> comp_section_narrative;

 								// echo '<pre>';
 								// print_r($tab_questions_ids);
 								// echo '</pre>';


 												if ($tab_questions_ids != '') {

 													// $comparisons_section_string = '<h1>' . $tab_name . '</h1>';
 													// $comparisons_section_string .= '<div class="comparison-section-narrative">' . $tab_narrative . '</div>';

 													// $comparisons_tab_return .= '<h1>' . $tab_name . '</h1>';
 													// $comparisons_tab_return .= '<div class="comparison-section-narrative">' . $tab_narrative . '</div>';
 													foreach ($tab_questions_ids as $key => $question_id) { ////////// Current question Loop

 														$current_question_return = '';

 														$count = $responses_count_by_question_and_iped[$key][$question_id];

 														// echo 'Count is ' . $count . '<br />';
 														// $percent_count = round( $count / $responses_by_iped * 100 );

 														$question_name          = $all_questions_list[$question_id] -> q_short_text;
 														$question_narrative     = $all_questions_list[$question_id] -> comparison_narrative;
 														$survey_question_number = $all_questions_list[$question_id] -> q_number;

 														$divizor = ( $responses_max[$question_id] -> max - $responses_min[$question_id] -> min);

 														if ($divizor > 0) {
 															$percent_lac = ($response_averages[$question_id] -> average - $responses_min[$question_id] -> min) / $divizor * 100;
 															$percent_lac = round($percent_lac, 2);


 															$percent_iped = ($response_averages_by_iped[$question_id] -> school_ave - $responses_min[$question_id] -> min) / $divizor * 100;
 															$percent_iped = round($percent_iped, 2);
 														}

 														$current_question_return .= '<div class="dm-comparison-question-block dm-question-' . $question_id . '" question-id="' . $question_id . '">';
 														$current_question_return .=  '<h4>' . $question_name . '</h4>';
 														$current_question_return .=  '<div class="question-narrative">' . $question_narrative . '</div>';

 														$average_lac_round 	= round( $response_averages[$question_id] -> average );
 														$average_iped_round = round( $response_averages_by_iped[$question_id] -> school_ave );

 														$response_lac 	= $question_options_list[$question_id][$average_lac_round] -> option_text;
 														$response_iped 	= $question_options_list[$question_id][$average_iped_round] -> option_text;

 														// $current_question_return .= 'Min: ' . $responses_min[$question_id] -> min . '<br />';
 														// $current_question_return .= 'Max: ' . $responses_max[$question_id] -> max . '<br /><br />';
 														//
 														// $current_question_return .= 'Response LAC: ' . $response_lac . '<br />';
 														// $current_question_return .= 'Response IPED: ' . $response_iped . '<br /><br />';
 														// $current_question_return .= 'Percent LAC: ' . $percent_lac . '<br />';
 														// $current_question_return .= 'Percent IPED: ' . $percent_iped . '<br /><br />';
 														// $current_question_return .= 'Average LAC: ' . $response_averages[$question_id] -> average . '<br />';
 														// $current_question_return .= 'Average IPED: ' . $response_averages_by_iped[$question_id] -> school_ave . '<br />';
 														// $current_question_return .= 'Average LAC (round): ' . $average_lac_round . '<br />';
 														// $current_question_return .= 'Average IPED (round): ' . $average_iped_round . '<br /><br />';

 														$thumbs = ($average_lac_round <= $average_iped_round) ? 'fa-thumbs-up' : 'fa-thumbs-down';
 														$color = ($average_lac_round <= $average_iped_round) ? 'green' : 'red';

 														// $current_question_return .=  '<strong style="color: orange">Response LAC: </strong>' . $response_lac . '<i class="fa ' . $thumbs . '" aria-hidden="true" style="color: ' . $color . '"></i> <br /><br />';
 														// $current_question_return .=  '<strong style="color: red">Response IPED: </strong>' . $response_iped . '<br /><br />';


 														$current_question_return .= '
 																<div class="row dm-option" option-percent="' . $option_percent . '">
 																	<div class="col-md-3 dm-option-label">
 																		<strong>' . get_the_title() . ':</strong> ' . $response_iped . '
 																	</div>
 																	<div class="col-md-9">
 																		<div class="progress">
 																			<div class="progress-bar progress-bar-comp-iped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent_iped . '%">
 																				<span class="dm-progress-bar-content">' . $percent_iped . '%</span>
 																			</div>
 																		</div>
 																	</div>
 																</div>';
 														$current_question_return .= '
 																<div class="row dm-option" option-percent="' . $option_percent . '">
 																	<div class="col-md-3 dm-option-label">
 																		<strong>All colleges:</strong>
 																		' . $response_lac . '
 																	</div>
 																	<div class="col-md-9">
 																		<div class="progress">
 																			<div class="progress-bar progress-bar-comp-lac" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent_lac . '%">
 																				<span class="dm-progress-bar-content">' . $percent_lac . '%</span>
 																			</div>
 																		</div>
 																	</div>
 																</div>';




 																// The old comparisons chart. See the CSS for it in dm-custom.css as deprecated
 														// $current_question_return .= '<div class="dm-comparison-chart">
 														// 	<div class="dm-marker dm-current-college-marker" style="left: ' . $percent_iped .'%"><span title="' . $response_lac . '">' .  get_the_title() . '</span></div>
 														// 	<div class="dm-marker dm-lac-marker" style="left: ' . $percent_lac . '%"><span title="' . $response_lac .'">LAC Average</span></div>
 														// </div>';



 														$current_question_return .= '</div>';

 														// $comparisons_tab_return .= $comparisons_tab_intro_string;
 														$comparisons_section_string .= $current_question_return;
 													} ////////// End Current question Loop

 												} // End If curent tab has questions
 												$comparisons_tab_return .= $comparisons_section_string;

 							// } // Aici se termina loopul pentru intrebarea asta

 							// echo $comparisons_tab_return;


 							$protected_box = '<div class="row dm-protected-box">
 																	<div class="col-md-2 dm-triangle">
 																		<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
 																	</div>
 																	<div class="col-md-10 dm-protected-narrative">
 																	<p>' . $tab -> protected_narrative . 	'</p>
 																	<p>To get access to this content, purchase a <a href="' . get_permalink( PAGE_GET_PREMIUM ) . '?rid=pv47iM">premium membership</a>.</p>
 																	</div>
 																</div>';


 							$allowed_shortcode = "[MM_Access_Decision access='true']" . $comparisons_tab_return . "[/MM_Access_Decision]";

 							$denied_shortcode = "[MM_Access_Decision access='false']" . $protected_box . "[/MM_Access_Decision]";

 							echo do_shortcode( $allowed_shortcode );
 							echo do_shortcode( $denied_shortcode );

 						}




 						// Regular tabs start from here

 						// If there are tabs questions, display them below.
             if ($tabs_questions[$tab_id] != '') {
 							// echo '<pre>';
 							// print_r($all_questions_list);
 							// echo '</pre>';
 							//
 							// echo '<pre>';
 							// print_r($tabs_questions[$tab_id]);
 							// echo '</pre>';
 							//
 							$current_tab_content_return = '';

               foreach ($tabs_questions[$tab_id] as $key => $question_id) {

 								// echo '<pre>';
 								// print_r($all_questions_list[$question_id]);
 								// echo '</pre>';

                 $question_name          = $all_questions_list[$question_id] -> q_short_text;
 								$question_narrative     = $all_questions_list[$question_id] -> tab_narrative;
                 $survey_question_number = $all_questions_list[$question_id] -> q_number;

 								$current_question_return = '';

 								$current_question_return .= '<div class="dm-question-block dm-question-' . $question_id . '" question-id="' . $question_id . '">';
                 $current_question_return .=  '<h4>' . $question_name . '</h4>';
 								$current_question_return .=  '<div class="question-narrative">' . $question_narrative . '</div>';



 								// For the moment display something
 								// $current_question_return .= 'Current question' . $question_id;

 								if ($question_id == 3 ) {
 									$average_lac 	= $response_averages[$question_id] -> average ;
 									$average_iped = $response_averages_by_iped[$question_id] -> school_ave ;

 									// $current_question_return .= 'Current question ' . $question_id . ' <br />';
 									// $current_question_return .= '$average_lac_round ' . $average_lac . ' <br />';
 									// $current_question_return .= '$average_iped_round ' . $average_iped . ' <br />';

 									switch ( $average_iped >= $average_lac ) {
 										case true:
 											// $current_question_return .= 'iped >= lac' . ' <br />';;
 											$comparison_class = 'above-average';
 											$hover_caption = 'This is above the LAC average which stands at ' . $average_lac;
 											break;
 										case false:
 											// $current_question_return .= 'iped < lac' . ' <br />';;
 											$comparison_class = 'below-average';
 											$hover_caption = 'This is below the LAC average which stands at ' . $average_lac;
 											break;
 									}

 										$current_question_return .= '<div class="dm-nps-container" title="' . $hover_caption . '">Net Promoter Score = <span class="' . $comparison_class . '">'  . $average_iped . '</span></div>';




 								} elseif ($question_id == 6 ) {

 									$average_lac 	= $response_averages[$question_id] -> average ;
 									$average_iped = $response_averages_by_iped[$question_id] -> school_ave ;

 									$average_lac_round 	= round( $response_averages[$question_id] -> average );
 									$average_iped_round = round( $response_averages_by_iped[$question_id] -> school_ave );

 									$response_lac 	= $question_options_list[$question_id][$average_lac_round] -> option_text;
 									$response_iped 	= $question_options_list[$question_id][$average_iped_round] -> option_text;

 									// $current_question_return .= 'Current question ' . $question_id . ' <br />';
 									// $current_question_return .= '$average_lac_round ' . $average_lac . ' <br />';
 									// $current_question_return .= '$average_iped_round ' . $average_iped . ' <br />';
 									// $current_question_return .= '$response_iped ' . $response_iped . ' <br />';
 									// $current_question_return .= '$average_iped_round ' . $average_iped_round . ' <br />';


 									$current_question_option = $charts_data[$question_id];
 									// echo '<pre>';
 									// print_r($current_question_option);
 									// echo '</pre>';
 									$current_option = $current_question_option[$average_iped_round];
 									// echo '<pre>';
 									// print_r( $current_option );
 									// echo '</pre>';

 									$option_text = $current_option -> option_text;
 									$option_percent_q6 = $average_iped / 10 * 100;

 									$current_question_return .= '<p class="dm-q6-chart-caption">On a 0 to 10 scale, this is what students rated ' . get_the_title() . '.</p>';
 									$current_question_return .= '
 											<div class="row dm-option" option-percent="' . $option_percent . '">
 												<div class="col-md-3 dm-option-label">

 													Satisfaction Level:
 												</div>
 												<div class="col-md-9">
 													<div class="progress">
 														<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $option_percent_q6 . '%">
 															<span class="dm-progress-bar-content">' . $option_percent_q6 . '%</span>
 														</div>
 													</div>
 												</div>
 											</div>';





 								} else { // Chart for all other questions

 										$current_question_chart_return = '';

 															$current_question_chart_return .= '<div class="dm-chart-container">';

 															// echo '<pre>';
 															// print_r($charts_data[$survey_question_number]);
 															// echo '</pre>';
 															$current_question_option = $charts_data[$question_id];


 															if ($current_question_option){
 																foreach ($current_question_option as $key => $current_option) {
 																	$option_text = $current_option -> option_text;
 																	$option_percent = $current_option -> percent;

 																	$current_question_chart_return .= '
 																			<div class="row dm-option" option-percent="' . $option_percent . '">
 																				<div class="col-md-3 dm-option-label">
 																					' . $option_text . '
 																				</div>
 																				<div class="col-md-9">
 																					<div class="progress">
 																						<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $option_percent . '%">
 																							<span class="dm-progress-bar-content">' . $option_percent . '%</span>
 																						</div>
 																					</div>
 																				</div>
 																			</div>';


 																}
 															}

 															$current_question_chart_return .= '</div>';

 															$current_question_return .=  $current_question_chart_return;
 								}




 								$response_id_array = array();
 								$selected_open_ended_question = $selected_open_ended_questions[$survey_question_number];

 								if ($selected_open_ended_question) {
 									foreach ($selected_open_ended_question as $response_id => $value) {
 										$response_id_array[] = $response_id;
 									}
 								}

 								$open_ended_responses = get_open_ended_responses_by_ids( $response_id_array );

 								if ($open_ended_responses) {

 									$what_students_say_return = '';

 									$what_students_say_return .= '<div class="dm-what-students-say">';
 									$what_students_say_return .= ' <h5> What students have to say? </h5>';


 									$what_students_say_return .= '<table class="dm-students-say-table">
 										<tr>
 											<th class="dm-question-number">No.</th>
 											<th class="dm-response-text">Reponse</th>
 											<th class="dm-graduation-year">Graduation</th>
 										</tr>';


 									$count = 0;

 									foreach ($open_ended_responses as $key => $open_response) {
 										$count++;

 										$what_students_say_return .= '<tr>
 														<td class="dm-question-number">' . $count . '</td>
 														<td class="dm-response-text">' . $open_response -> response_other . '</td>
 														<td class="dm-graduation-year">' . $open_response -> graduation_year . '</td>
 													</tr>';
 									}

 									$what_students_say_return .= '</table>';
 									$what_students_say_return .= '</div>';

 									$what_students_say_return .= 'gigelgelelellellellelellelele' . dm_generate_featured_answers_front_end( $post_id, $q_all_number );


 								$current_question_return .= $what_students_say_return;
 							}

 								$current_question_return .=  '</div>';

 								$current_tab_content_return .= $current_question_return;


               }

 							// echo $current_tab_content_return;

 							$protected_box = '<div class="row dm-protected-box">
 																	<div class="col-md-2 dm-triangle">
 																		<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
 																	</div>
 																	<div class="col-md-10 dm-protected-narrative">
 																	<p>' . $tab -> protected_narrative . 	'</p>
 																	<p>To get access to this content, purchase a <a href="' . get_permalink( PAGE_GET_PREMIUM ) . '?rid=pv47iM">premium membership</a>.</p>
 																	</div>
 																</div>';


 							$allowed_shortcode = "[MM_Access_Decision access='true']" . $current_tab_content_return . "[/MM_Access_Decision]";

 							$denied_shortcode = "[MM_Access_Decision access='false']" . $protected_box . "[/MM_Access_Decision]";

 							echo do_shortcode( $allowed_shortcode );
 							echo do_shortcode( $denied_shortcode );
             }


 	          echo '</div>';
           }
            ?>




 		    </div>
 		</div>

 		<!-- This is where the TAB code goes -->

 	<?php
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
