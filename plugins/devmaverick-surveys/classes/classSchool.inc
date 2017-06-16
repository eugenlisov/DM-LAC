<?php
/**
 * Use like this: $dm_school = new DM_School;
 */
class DM_School {




  /**
   * Get the IPED
   * If $school_id provided, return IPED for that colelge. Otherwise, return IPED for the current school
   * @return [int] IPED
   */
  public function get_iped( $school_id = '' ) {

    if ( !$school_id ) $school_id = get_the_ID();

    $college_iped = get_post_meta( $school_id, 'school_iped', true );

    return $college_iped;
  }

  public function get_nps() {

    $iped = $this -> get_iped();

    $dm_nps = new DM_NPS;
    $nps = $dm_nps -> get_nps( $iped );

    return $nps;
  }


  public function get_school_nps ( $school_id = '' ) {
    if ( ! $school_id ) {
      return false;
    }

    $iped = $this -> get_iped( $school_id );

    $dm_nps = new DM_NPS;
    $nps = $dm_nps -> get_nps( $iped );

    return $nps;

  }

  public function get_school_selectivity( $school_id = '' ) {

    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }
    $terms = wp_get_post_terms( $school_id, 'selectivity' );
    if ( ! $terms ) {
      return false;
    }
    $selectivity = $terms[0] -> name;
    return $selectivity;

  }

  public function get_school_location( $school_id = '' ) {

    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }
    $terms_city = wp_get_post_terms( $school_id, 'city' );
    $terms_state = wp_get_post_terms( $school_id, 'state' );

    if ( $terms_city ) {
      $location['city'] = $terms_city[0] -> name;
    }
    if ( $terms_state ) {
      $location['state'] = $terms_state[0] -> name;
    }

    if ( $location ) {
      return implode( ', ', $location);
    } else {
      return false;
    }

  }



  /**
   * Get school contact data
   * If $school_id provided, return data for that colelge. Otherwise, return data for the current school
   * @return [int] IPED
   */
  public function get_contact_data ( $school_id = '' ) {

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

  public function get_respondents_count( $school_id = '' ) {

    if  ( ! $school_id ) {
      $school_id = get_the_ID();
    }

    $iped = $this -> get_iped( $school_id );

    $sql = 'SELECT count( DISTINCT(`respondent_id`) ) AS respondent_count FROM `dm_survey_responses` WHERE iped = ' . $iped;

    global $wpdb;

    $result = $wpdb->get_row( $sql );

    if ( $result ) {
      return $result -> respondent_count;
    } else {
      return false;
    }

  }

  /**
   * Only used for the School Actions Bar.
   * @return [type] [description]
   */
  public function dm_my_colleges_widget( $limit = 99 ) {

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

      $count = 0;
  		foreach ($favorites as $key => $college_id) {
        if ( $count == $limit ) break;
        $count++;

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

  /**
   * Only used for the School Actions Bar.
   * @return [type] [description]
   */
  public function count_my_colleges() {

  	$user_id = get_current_user_id();
  	$favorites = get_user_meta($user_id, 'simplefavorites');
  	$favorites = $favorites[0][0]['posts'];
    return count( $favorites );

  }


  public function ajax_add_school_note() {

    $ajax_data = $_POST['data'];
    // print_r ( $ajax_data );

    $note = $ajax_data['note'];
    $post_id = $ajax_data['post_id'];
    $user_id = $ajax_data['user_id'];

    $existing_school_notes = get_user_meta($user_id, 'dm_user_notes', true);

    // If it already exists and is the same value, return true;
    if ( $existing_school_notes[$post_id] == $note ) {
      return true;
    }

    // Otherwise, continue as usual.
    $existing_school_notes[$post_id] = $note;

    $result = update_user_meta($user_id, 'dm_user_notes', $existing_school_notes);

    // so check and make sure the stored value matches $new_value
// if ( get_user_meta($user_id,  'some_meta_key', true ) != $new_value )
// 	wp_die('An error occurred');

    return $result;

  }


  public function ajax_add_school_rating() {

    $ajax_data = $_POST['data'];

    $rating	 = $ajax_data['rating'];
    $post_id = $ajax_data['post_id'];
    $user_id = $ajax_data['user_id'];

    $existing_school_ratings = get_user_meta($user_id, 'dm_user_ratings', true);

    // If it already exists and is the same value, return true;
    if ( $existing_school_ratings[$post_id] == $rating ) {
      return true;
    }

    // Otherwise, continue as usual.
    $existing_school_ratings[$post_id] = $rating;

    $result = update_user_meta($user_id, 'dm_user_ratings', $existing_school_ratings);

    return $result;

  }





} // End Class

?>
