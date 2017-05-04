<?php
/**
 * Use like this: $dm_note = new DM_Note;
 */
class DM_Note {

  public function get_user_school_note( $school_id = '', $user_id = '' ) {
    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id = get_current_user_id();
    }

    $user_note  = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];

    return $user_note;
  }


} // End class
