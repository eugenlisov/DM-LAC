<?php
/*
* Initiate all shortcodes
*/
add_action( 'init', 'register_shortcodes');

/*
* Register all shortcodes
*/
function register_shortcodes(){
	add_shortcode('dm-school-tabs', 'dm_school_tabs');
	add_shortcode('dm-wishlist', 'dm_wishlist');
	add_shortcode('dm-dashboard-links', 'dm_dashboard_links');
	add_shortcode('dm-dashboard-my-colleges', 'dm_dashboard_my_colleges');

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



function dm_wishlist() {

	$user_id = get_current_user_id();
	$favorites = get_user_meta($user_id, 'simplefavorites');
	$favorites = $favorites[0][0]['posts'];

	// echo '<pre>';
	// print_r($favorites);
	// echo '</pre>';

	$post_notes = $_POST;
	if ($post_notes) {

		$notes_array = [];

		foreach ($post_notes as $key_note => $note) {

			$note = trim($note);
			$fav_college_id = str_replace("note-","",$key_note);
			$notes_array[$fav_college_id] = $note;

		}
		update_user_meta( $user_id, 'dm_user_notes', $notes_array );

	}

	$saved_user_notes 				= get_user_meta( $user_id, 'dm_user_notes', true );
	$existing_school_ratings 	= get_user_meta( $user_id, 'dm_user_ratings', true );

	$favorites_count = count($favorites);

	$return_string = '';


	$return_string .= '<p><strong>Total Colleges in My List:</strong> ' . $favorites_count . '</p>';

	$return_string .= '<form action="" method="POST">';

	if ($favorites_count > 0) {
		foreach ($favorites as $key => $college_id) {

			$return_string .= '<div class="row">';
			$return_string .= '	<div class="col-md-4">';
			$return_string .= '		<p><a href="' . get_the_permalink( $college_id ) . '">' . get_the_title( $college_id ) . '</a></p>';
			$return_string .= '	</div>';

			// $image      = get_the_post_thumbnail( $college_id );
      // $image_url  = wp_get_attachment_image_src( $image, 'full', false )[0];
			//
			// echo '<pre>';
			// print_r($image);
			// echo '</pre>';
			// $return_string .= $image_url;

			$return_string .= '	<div class="col-md-8 my-colleges-note">';



					$rating = $existing_school_ratings[ $college_id ];
					if ($rating != '') {
						$return_string .= '	<p>';
						$return_string .= '<strong>Your rating: ' . $rating . '</strong>';
						$return_string .= '<span class="dm-rating">';
						for ($i=1; $i <= $rating; $i++) {
							$return_string .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
						}
						$return_string .= '</span>';
						$return_string .= '	</p>';
					}


			$return_string .= '		<p>Note:</p>';
			$return_string .= '		<textarea rows="2" cols="50" name="note-' . $college_id . '">' . $saved_user_notes[$college_id] . '</textarea>';
			$return_string .= '		<br /><br />';
			$return_string .= '	</div>';
			$return_string .= '</div>';

		}
	}

	$return_string .= '<input type="submit" value="Save notes">';
	$return_string .= '</form>';

	return $return_string;

}





/**
 * TODO Possible deprecated
 * @return [type] [description]
 */
function dm_dashboard_my_colleges( $limit = 999 ) {



	$user_id = get_current_user_id();
	$favorites = get_user_meta($user_id, 'simplefavorites');
	$favorites = $favorites[0][0]['posts'];
	$school_ratings 	= get_user_meta( $user_id, 'dm_user_ratings', true );



	// echo '<pre>';
	// print_r($favorites);
	// echo '</pre>';

	$return_string = '<div class="dm-my-collges-widget">';

	$favorites_count = count($favorites);



	if ($favorites_count > 0) {

		$return_string .= '<h3>My Colleges</h3>';
		$count = 1;
		foreach ($favorites as $key => $college_id) {

			$college_name 			= get_the_title( $college_id );
			$college_permalink 	= get_the_permalink( $college_id );


			// $thumb = get_the_post_thumbnail( $college_id, 'thumbnail' );
			//
			// echo '<pre>';
			// print_r($thumb);
			// echo '</pre>';


			$return_string .= '<div class="college-item">';

			$return_string .= '<a href=" ' . $college_permalink . '">' . $college_name . '</a><br />';

			$rating = $school_ratings[$college_id];
			if ($rating) {
				$return_string .= 'Your rating:';
				$return_string .= '<span class="dm-rating">';
				for ($i=1; $i <= $rating; $i++) {
					$return_string .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
				}
				$return_string .= '</span>';
			}

			$return_string .= '</div>';

		}
	}

	$return_string .= '</div>';

	return $return_string;

}









function dm_dashboard_links() {
	$return_string =  '<div class="dm-dashboard-links">';

	$return_string .= '<a href="' . get_permalink( PAGE_MY_ACCOUNT ) . '" class="dm-dashboard-button col-md-3">My Account <i class="fa fa-user" aria-hidden="true"></i></a>';
	$return_string .= '<a href="' . get_permalink( PAGE_MY_COLLEGES ) . '" class="dm-dashboard-button col-md-3">My Colleges <i class="fa fa-university" aria-hidden="true"></i></a>';
	$return_string .= '<a href="' . get_permalink( CONTACT_PAGE ) . '" class="dm-dashboard-button col-md-3">Contact <i class="fa fa-envelope" aria-hidden="true"></i></a>';


	$return_string .=  '</div>';

	return $return_string;
}











 ?>
