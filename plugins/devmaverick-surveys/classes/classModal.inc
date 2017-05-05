<?php
/**
 * Use like this: $dm_modal = new DM_Modal;
 */
class DM_Modal {



  public function college_rating_modal() {

    $return = '';
    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-school-rating-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">

          </div>
        </div>
      </div>
    </div>';

    return $return;
  }
  public function modal_content_rating_fresh() {

    $dm_rating = new DM_Rating;

    $school_id      = get_the_ID();
    $editable_ratings_block   = $dm_rating -> get_editable_ratings_block( $school_id );

    // echo 'fresh rating';

    $return = '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h2 class="modal-title" id="mySmallModalLabel">Add Rating:</h2>
              </div>
              <div class="modal-body">
                  <div class="dm-modal-editable-rating-conteiner" style="display: block;">
                      ' . $editable_ratings_block . '
                      <p class="dm-rating-action trigger-cancel-rating-change">Cancel</p>
                  </div>
              </div>';

    return $return;

  }
  public function modal_content_rating_existing( $school_id = '', $user_id = '' ) {

    $dm_rating = new DM_Rating;

    if ( ! $school_id ) {
      $school_id      = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id        = get_current_user_ID();
    }


    $editable_ratings_block   = $dm_rating -> get_editable_ratings_block( $school_id );
    $fixed_ratings_block      = $dm_rating -> get_fixed_ratings_block( $school_id, $user_id );

    // echo 'existing rating';

    $return = '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h2 class="modal-title" id="mySmallModalLabel">Your Rating:</h2>
              </div>
              <div class="modal-body">
                  <div class="dm-modal-fixed-rating-conteiner">
                      ' . $fixed_ratings_block . '
                      <p class="dm-rating-action trigger-change-rating">Change your rating</p>
                  </div>
                  <div class="dm-modal-editable-rating-conteiner">
                      ' . $editable_ratings_block . '
                      <p class="dm-rating-action trigger-cancel-rating-change">Cancel</p>
                  </div>
              </div>';

    return $return;

  }


  public function college_note_modal( $school_id = '', $user_id = '' ) {
    $dm_rating = new DM_Rating;
    $dm_note = new DM_Note;

    if ( ! $school_id ) {
      $school_id      = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id        = get_current_user_ID();
    }

    $ratings_block  = $dm_rating -> get_editable_ratings_block( $school_id );
    $user_note      = $dm_note -> get_user_school_note( $school_id, $user_id );
    $rating 	      = $dm_rating -> get_user_school_rating( $school_id, $user_id );

    $return = '';
    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-add-note-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">

          </div>
        </div>
      </div>
    </div>';

    return $return;
  }

  public function modal_content_note_fresh( $school_id = '', $user_id = '' ) {

    $dm_note = new DM_Note;

    if ( ! $school_id ) {
      $school_id      = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id        = get_current_user_ID();
    }

    $user_note      = $dm_note -> get_user_school_note( $school_id, $user_id );

    $return = '<div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <h2 class="modal-title" id="mySmallModalLabel">Add a note about<br /> ' . get_the_title( $school_id ) . '</h2>
                </div>
                <div class="modal-body">

                  <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
                  <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green">Save Note</button></p>
                  <p><strong>Your rating: </strong>
                    ' . $ratings_block . '
                  </p>
                </div>';

    return $return;

  }


  public function modal_content_note_existing( $school_id = '', $user_id = '' ) {

    $dm_note = new DM_Note;

    if ( ! $school_id ) {
      $school_id      = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id        = get_current_user_ID();
    }

    $user_note      = $dm_note -> get_user_school_note( $school_id, $user_id );

    $return = '<div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <h2 class="modal-title" id="mySmallModalLabel">Your Thoughts on <br /> ' . get_the_title( $school_id ) . '</h2>
                </div>
                <div class="modal-body">
                  <div class="dm-modal-existing-note-container">
                    <p><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>' . $user_note . '</p>
                    <p class="dm-note-action trigger-edit-note">Edit note</p>
                  </div>
                  <div class="dm-modal-editable-note-container" style="display: none;">
                    <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
                    <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green trigger-save-note">Save Note</button></p>
                    <p class="dm-note-action trigger-cancel-note-edit">Cancel</p>
                  </div>

                </div>';

    return $return;

  }




  public function college_contact_modal() {
    $dm_school = new DM_School;

    $school_id = get_the_ID();

    $contact_data  = $dm_school -> get_contact_data ( $school_id );

    $contact_school_modal = '';

    $contact_school_modal .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-contact-school-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h2 class="modal-title" id="mySmallModalLabel">Contact ' . get_the_title() . '</h2>
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
      </div>

    </div>';

    return $contact_school_modal;
  }


  public function ajax_get_school_review_modal() {

    $school_id = $_POST['data'];
    if ( ! $school_id ) {
      return false;
    }
    $user_id = get_current_user_ID();


    $dm_rating = new DM_Rating;
    $rating 	 = $dm_rating -> get_user_school_rating( $school_id, $user_id );

    if ( $rating ) {
      $return = $this -> modal_content_rating_existing( $school_id, $user_id );
    } else {
      $return = $this -> modal_content_rating_fresh( $school_id, $user_id);
    }

    return $return;

  }

  public function ajax_get_school_note_modal() {

    $school_id = $_POST['data'];
    if ( ! $school_id ) {
      return false;
    }
    $user_id = get_current_user_ID();


    $dm_rating = new DM_Rating;
    $rating 	 = $dm_rating -> get_user_school_rating( $school_id, $user_id );

    if ( $rating ) {
      $return = $this -> modal_content_note_existing( $school_id, $user_id );
    } else {
      $return = $this -> modal_content_note_fresh( $school_id, $user_id);
    }

    return $return;

  }


} // End class
