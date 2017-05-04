<?php
/**
 * Use like this: $dm_rating = new DM_Rating;
 */
class DM_Rating {

  public function get_user_school_rating( $school_id = '', $user_id = '' ) {
    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id = get_current_user_id();
    }

    $rating = get_user_meta( $user_id, 'dm_user_ratings', true )[$school_id];

    return $rating;
  }


  public function get_empty_ratings_block() {
    $ratings_block = '<span class="dm-rating">';
    for ($i=1; $i < 6; $i++) {
      $ratings_block .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
    }

    $ratings_block .= '</span>';

    return $ratings_block;
  }

} // End class
