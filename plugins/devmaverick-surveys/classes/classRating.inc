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


  public function get_editable_ratings_block( $school_id = '' ) {

    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }

    $rating = $this -> get_user_school_rating( $school_id );

    $ratings_block = '<span class="dm-rating">';
    for ($i=1; $i < 6; $i++) {
      $filled = ( $i <= $rating ) ? 'filled' : '';
      $ratings_block .= '<i class="fa fa-star fa-3x ' . $filled . '" aria-hidden="true" rating="' . $i . '"></i>';
    }

    $ratings_block .= '</span>';

    return $ratings_block;
  }

  public function get_my_college_rating_block( $college_id ) {

    if ( ! $college_id ) {
      return false;
    }

    $rating = $this -> get_user_school_rating( $college_id );


    $return = '<div class="m-b-md dm-my-school-rating-block">';

    if  ( $rating ) {
      $return .= '<span class="dm-my-college-meta-item">Your Rating: </span>';
      for ($i=1; $i <= $rating ; $i++) {
        $return .= '<i class="fa fa-star fa-2x"></i>';
      }
    } else {
      $return .= '<span class="dm-my-college-meta-item">You haven\'t yet rated this college! </span>';
    }


    $return .= '</div>';

    return $return;

  }

  public function get_fixed_ratings_block( $school_id = '', $user_id = '' ) {

    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id = get_current_user_ID();
    }

    $rating = $this -> get_user_school_rating( $school_id, $user_id );

    $ratings_block = '<div class="dm-fixed-rating">';
    for ($i=1; $i <= $rating; $i++) {
      $ratings_block .= '<i class="fa fa-star fa-3x filled" aria-hidden="true" rating="' . $i . '"></i>';
    }

    $ratings_block .= '</div>';

    return $ratings_block;
  }

} // End class
