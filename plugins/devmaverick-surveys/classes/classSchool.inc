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






} // End Class
?>