<?php
/**
 * Use like this: $dm_note = new DM_Note;
 */
class DM_Note {

  public function get_user_school_note( $school_id = '' ) {
    if ( ! $school_id ) {
      $school_id = get_the_ID();
    }

    $current_user_notes 	= unserialize( CURRENT_USER_SAVED_NOTES );

    // echo '<pre>';
    // print_r( $current_user_notes );
    // echo '</pre>';

    if ( $current_user_notes[$school_id] ) {
      return $current_user_notes[$school_id];
    } else {
      return false;
    }
  }



  public function my_college_note_block( $school_id = '', $user_id = '' ) {
    if ( ! $school_id ) {
      return false;
    }
    if ( ! $user_id ) {
      $user_id = get_current_user_id();
    }

    $note = $this -> get_user_school_note ( $school_id );


    if ( ! $note ) {
      $return = $this -> my_my_college_note_fresh( $school_id );
    } else {
      $return = $this -> my_my_college_note_existing( $school_id );
    }

    return $return;
  }

  private function my_my_college_note_fresh( $school_id = '' ) {
    if ( ! $school_id ) {
      return false;
    }

    $return = '<h4>You haven\'t yet added any notes on ' . get_the_title( $school_id ) . '. Add one now!</h4>
                <p>
                  <textarea class="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="Write your thoughts...">' . $user_note . '</textarea>
              </p>
              <p>
                  <button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green trigger-my-colleges-save-note">Add Note</button>
              </p>';

    return $return;
  }
  private function my_my_college_note_existing( $school_id = '' ) {
    if ( ! $school_id ) {
      return false;
    }

    $note = $this -> get_user_school_note ( $school_id );

    $return = '<h4>Your thoughts on ' . get_the_title( $school_id ) . ':</h4>
              <div class="dm-my-colleges-existing-note-container">
                <p><i class="fa fa-quote-left fa-2x" aria-hidden="true"></i>' . $note . '</p>
                <p class="dm-note-action trigger-my-colleges-edit-note">Edit note</p>
              </div>
              <div class="dm-my-colleges-editable-note-container" style="display: none;">
                <p><textarea class="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title( $school_id ) . '? Add it here so you remember later.">' . $note . '</textarea></p>
                <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green trigger-my-colleges-save-note">Save Note</button></p>
                <p class="dm-note-action trigger-my-colleges-cancel-note-edit">Cancel</p>
              </div>';

    return $return;
  }


} // End class
