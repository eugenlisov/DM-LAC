<?php



// The  associate IPED to School function
add_action( 'wp_ajax_nopriv_dm_associate_iped_to_school', 'dm_associate_iped_to_school' );
add_action( 'wp_ajax_dm_associate_iped_to_school', 'dm_associate_iped_to_school' );

function dm_associate_iped_to_school() {
	$school_data = $_POST['data'];

	$school_id = $school_data['school_id'];
	$iped = $school_data['selected_iped'];

	$previous_iped = get_post_meta($school_id, 'school_iped', true);
	print_r($previous_iped);


	update_post_meta($school_id, 'school_iped', $iped);

	if ($iped == '') {

		// $previous_iped = get_post_meta($school_id, 'school_iped', true);
		echo 'Aici ar trebui sters averageurile vechi pentru ' . $previous_iped;
			if ($previous_iped != '') {
				delete_averages_by_iped( $previous_iped );
			}

	} else {
		echo 'Aici calculam averageul pentru ' . $iped;
		calculate_averages_by_iped( $iped);
	}




	// echo 'costel';

	print_r($school_data);
	// $product_id 	= $product_data['product_id'];
	// $quantity 		= $product_data['quantity'];
	// global $woocommerce;
	// $add_to_cart = $woocommerce->cart->add_to_cart($product_id, $quantity);
	// if ( $add_to_cart == true ) {
	//   $result = 'inserted';
	// } else {
	// 	$result = 'not inserted';
	// }
  // die(json_encode( $result ));
}





// The  associate IPED to School function
add_action( 'wp_ajax_nopriv_dm_associate_highhrise_id_to_school', 'dm_associate_highhrise_id_to_school' );
add_action( 'wp_ajax_dm_associate_highhrise_id_to_school', 'dm_associate_highhrise_id_to_school' );

function dm_associate_highhrise_id_to_school() {
	$school_data = $_POST['data'];

	// print_r($school_data);

	$school_id = $school_data['school_id'];
	$highrise_id = $school_data['selected_highrise_id'];

	update_post_meta($school_id, 'school_highrise_id', $highrise_id);

	echo 'Should be success';

}




// The recalculate scool averages function function
add_action( 'wp_ajax_nopriv_dm_recalculate_averages', 'dm_recalculate_averages' );
add_action( 'wp_ajax_dm_recalculate_averages', 'dm_recalculate_averages' );

// This function is also triggered after an import of the surveys

function dm_recalculate_averages() {

	$associated_ipeds = get_associated_ipeds();

	foreach ($associated_ipeds as $key => $iped) {
		calculate_averages_by_iped( $iped );
		// print_r( 'done for ' . $iped );
	}

	echo '<br />Success recalculating averages';

}




//
//
add_action( 'wp_ajax_nopriv_dm_save_tab_data', 'dm_save_tab_data' );
add_action( 'wp_ajax_dm_save_tab_data', 'dm_save_tab_data' );

function dm_save_tab_data() {
	$tab_data = $_POST['data'];

	// print_r($tab_data);

	$action = $tab_data['action'];
	$tab_id = $tab_data['tab_id'];
	$question_id = $tab_data['question_id'];

	global $wpdb;

	if ($action == 'remove') {

			$sql_delete = '
			DELETE FROM `dm_survey_tab_questions`
			 WHERE tab_id = ' . $tab_id . '
			 AND question_id = ' . $question_id . '
			';

			echo $sql_delete;

			$wpdb->query( $sql_delete );

	} elseif ($action == 'add') {

		$wpdb->insert(
			'dm_survey_tab_questions',
			array(
				'tab_id' 			=> $tab_id,
				'question_id' => $question_id
			),
			array(
				'%d',
				'%d'
			)
		);
	}

}



//
//
add_action( 'wp_ajax_nopriv_dm_save_tab_narratives', 'dm_save_tab_narratives' );
add_action( 'wp_ajax_dm_save_tab_narratives', 'dm_save_tab_narratives' );

function dm_save_tab_narratives() {
	$tab_narratives = $_POST['data'];

	// print_r($tab_narratives);

	$tab_id 			= $tab_narratives['tab-id'];
	$content_type = $tab_narratives['content-type'];
	$content_text = $tab_narratives['current-text'];


	global $wpdb;

	switch ($content_type) {
		case 'tab-intro':
			$update_column = 'tab_intro';
			break;
		case 'comparison-narrative':
			$update_column = 'comp_section_narrative';
			break;
		case 'protected-content-narrative':
			$update_column = 'protected_narrative';
			break;
	}


	$sql_query = '
	UPDATE dm_survey_tabs
		SET ' . $update_column . ' = "' . $content_text . '"
		WHERE id = ' . $tab_id . ';
	';

	echo $sql_query;

	$wpdb->query( $sql_query );

}


//
//
add_action( 'wp_ajax_nopriv_dm_save_open_ended_questions', 'dm_save_open_ended_questions' );
add_action( 'wp_ajax_dm_save_open_ended_questions', 'dm_save_open_ended_questions' );

function dm_save_open_ended_questions() {

	$dm_question = new DM_Question;

	$featured_answers_block = $dm_question -> ajax_save_open_question();

 	die(json_encode( $featured_answers_block ));

}



//
//
add_action( 'wp_ajax_nopriv_dm_remove_open_ended_questions', 'dm_remove_open_ended_questions' );
add_action( 'wp_ajax_dm_remove_open_ended_questions', 'dm_remove_open_ended_questions' );

function dm_remove_open_ended_questions() {

	$dm_question = new DM_Question;

	$featured_answers_block = $dm_question -> ajax_remove_open_question();

	die(json_encode( $featured_answers_block ));

}


//
//
add_action( 'wp_ajax_nopriv_dm_save_school_note', 'dm_save_school_note' );
add_action( 'wp_ajax_dm_save_school_note', 'dm_save_school_note' );

function dm_save_school_note() {
	$ajax_data = $_POST['data'];

	$note = $ajax_data['note'];
	$post_id = $ajax_data['post_id'];
	$user_id = $ajax_data['user_id'];


	$existing_school_notes = get_user_meta($user_id, 'dm_user_notes', true);
		print_r($existing_school_notes);

	$existing_school_notes[$post_id] = $note;

	$result = update_user_meta($user_id, 'dm_user_notes', $existing_school_notes);


	print_r($result);
	print_r($existing_school_notes);

}


//
//
add_action( 'wp_ajax_nopriv_dm_save_school_rating', 'dm_save_school_rating' );
add_action( 'wp_ajax_dm_save_school_rating', 'dm_save_school_rating' );

function dm_save_school_rating() {
	$ajax_data = $_POST['data'];

	print_r($ajax_data);

	$rating	 = $ajax_data['rating'];
	$post_id = $ajax_data['post_id'];
	$user_id = $ajax_data['user_id'];



	$existing_school_ratings = get_user_meta($user_id, 'dm_user_ratings', true);

	print_r($existing_school_ratings);

	$existing_school_ratings[$post_id] = $rating;



	$result = update_user_meta($user_id, 'dm_user_ratings', $existing_school_ratings);


	print_r($result);
	print_r($existing_school_ratings);

}


//
//
add_action( 'wp_ajax_nopriv_dm_save_question_narratives', 'dm_save_question_narratives' );
add_action( 'wp_ajax_dm_save_question_narratives', 'dm_save_question_narratives' );

function dm_save_question_narratives() {
	$tab_narratives = $_POST['data'];

	// print_r($tab_narratives);

	$question_id 	= $tab_narratives['question-id'];
	$content_type = $tab_narratives['content-type'];
	$content_text = $tab_narratives['current-text'];


	global $wpdb;

	switch ($content_type) {
		case 'tab-narrative':
			$update_column = 'tab_narrative';
			break;
		case 'comparison-narrative':
			$update_column = 'comparison_narrative';
			break;
	}


	$sql_query = '
	UPDATE dm_survey_all_questions
		SET ' . $update_column . ' = "' . $content_text . '"
		WHERE id = ' . $question_id . ';
	';

	// echo $sql_query;

	$wpdb->query( $sql_query );

}










 ?>
