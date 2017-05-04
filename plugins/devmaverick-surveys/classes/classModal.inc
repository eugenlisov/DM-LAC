<?php
/**
 * Use like this: $dm_modal = new DM_Modal;
 */
class DM_Modal {



  public function college_rating_modal() {
    $dm_rating = new DM_Rating;

    $ratings_block  = $dm_rating -> get_empty_ratings_block();
    $user_id        = get_current_user_id();
    $school_id      = get_the_ID();
    $user_note      = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];
    $rating 	      = $dm_rating -> get_user_school_rating( $school_id, $user_id );

    $return = '';
    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-add-note-rating-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h2 class="modal-title" id="mySmallModalLabel">Add a note about<br /> ' . get_the_title() . $rating . '</h2>
            </div>
            <div class="modal-body">

              <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
              <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green">Save Note</button></p>
              <p><strong>Your rating: </strong>
                ' . $ratings_block . '
              </p>
              <p><small>(So you later know what you thought of this)</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>';

    return $return;
  }


  public function college_note_modal() {
    $dm_rating = new DM_Rating;
    $dm_note = new DM_Note;

    $ratings_block  = $dm_rating -> get_empty_ratings_block();
    $user_id        = get_current_user_id();
    $school_id      = get_the_ID();
    $user_note      = $dm_note -> get_user_school_note( $school_id, $user_id );
    $rating 	      = $dm_rating -> get_user_school_rating( $school_id, $user_id );

    $return = '';
    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-add-note-rating-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h2 class="modal-title" id="mySmallModalLabel">Add a note about<br /> ' . get_the_title() . '</h2>
            </div>
            <div class="modal-body">

              <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
              <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green">Save Note</button></p>
              <p><strong>Your rating: </strong>
                ' . $ratings_block . '
              </p>
              <p><small>(So you later know what you thought of this)</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>';

    return $return;
  }


} // End class
