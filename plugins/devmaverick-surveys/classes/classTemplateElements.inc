<?php
/**
 * Use like this $dm_template_elements = new DM_TemplateElements;
 */
class DM_TemplateElements {


  public function dm_get_school_actions_block() {

      if (is_singular( 'schools' )) {

          if (is_user_logged_in()) {
            $content = $this -> get_logged_in_content();
            $modal   = $this -> get_logged_in_modal();
          } else {
            $content = $this -> get_logged_out_content();
            $modal   = $this -> get_logged_out_modal();
          }

          $contact_modal  = $this -> get_school_contact_modal();
          $return = $content . $modal . $contact_modal;

          return $return;

        }

  }


  public function get_school_contact_modal() {

    $school_id = get_the_ID();
    $contact_data  = get_school_contact_data ( $school_id );

    $contact_school_modal = '';

    $contact_school_modal .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-contact-school-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title" id="mySmallModalLabel">Contact ' . get_the_title() . '</h4>
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

  public function get_logged_out_modal() {

    $return = '';

    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-login-upgrade-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title" id="mySmallModalLabel">Log in or Upgrade</h4>
            </div>
            <div class="modal-body">
              <p>To make use of this feature, you need to log in first.</p>
              <p> <a href="' . get_permalink( LOG_IN_PAGE ) . '" class="btn btn-default">Log In</a> <a href="' . get_permalink( PAGE_GET_PREMIUM ) . '?rid=pv47iM" class="btn btn-default">Get Premium</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>';

    return $return;
  }

  public function get_logged_in_modal() {

    $ratings_block  = $this -> get_ratings_block();
    $user_id        = get_current_user_id();
    $school_id      = get_the_ID();
    $user_note      = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];
    $rating 	      = get_user_meta( $user_id, 'dm_user_ratings', true )[$school_id];

    $return = '';
    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-add-note-rating-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title" id="mySmallModalLabel">Add a note about ' . get_the_title() . '</h4>
            </div>
            <div class="modal-body">

              <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
              <p><button id="dm-single-note-button" type="button" class="btn btn-primary">Save Note</button></p>
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

  public function get_logged_in_content() {

      $user_id = get_current_user_id();
      $school_id = get_the_ID();
      $user_note = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];

      $rating_string = $this -> get_my_rating_block();

      $dm_school = new DM_School;
      $my_colleges_widget = $dm_school -> dm_my_colleges_widget( 10 );
      $my_colleges_count  = $dm_school -> count_my_colleges();

      $logged_in_content = '<div class="row dm-college-top-content logged-in">
                              <div class="col-md-2"><button type="button" class="btn btn-primary btn-contact-school trigger-contact-school-modal"><i class="fa fa-phone" aria-hidden="true"></i> Contact</button></div>
                              <div class="col-md-4">' . do_shortcode('[favorite_button]') . '</div>
                              <div class="col-md-3 dm-my-rating">
                                  ' . $rating_string . '
                              </div>
                              <div class="col-md-2 dm-my-note">
                                  <div class="row">
                                    <div class="col-md-1"><i class="fa fa-sticky-note" aria-hidden="true"></i></div>
                                    <div class="col-md-10 user-note">Note</div>
                                  </div>
                                  <div class="dm-hover-content notes">
                                    <div class="dm-notes-headline"><h3>My notes on ' . get_the_title() . '</h3></div>
                                    <div class="dm-notes-container">' . $user_note . '</div>
                                    <small class="dm-edit-note-rating trigger-login-upgrade-modal">Edit note & rating <i class="fa fa-pencil" aria-hidden="true"></i> </small>
                                  </div>
                              </div>
                              <div class="col-md-1 dm-my-colleges">
                                <div class="row edit-user-note">
                                  ' . $my_colleges_count . '
                                  <i class="fa fa-university" aria-hidden="true"></i>

                                </div>
                                <div class="dm-hover-content colleges">
                                    ' . $my_colleges_widget . '
                                    <small><a href="' . get_permalink( PAGE_MY_COLLEGES ) . '">See all your colleges <i class="fa fa-university" aria-hidden="true"></i></a></small>
                                </div>
                              </div>
                            </div>';

      return $logged_in_content;
  }

  public function get_logged_out_content() {
    $logged_out_content = '<div class="dm-college-top-content logged-out">
                            <div class="col-md-3"><button type="button" class="btn btn-primary btn-contact-school trigger-contact-school-modal"><i class="fa fa-phone" aria-hidden="true"></i> Contact</button></div>
                            <div class="col-md-9">
                              <button class="dm-add-favorite-logged-out trigger-login-upgrade-modal">Add to My Colleges<i class="sf-icon-star-empty"></i></button>
                            </div>
                          </div>';

    return $logged_out_content;
  }

public function get_my_rating_block() {

  $user_id    = get_current_user_id();
  $school_id  = get_the_ID();
  $rating 	  = get_user_meta( $user_id, 'dm_user_ratings', true )[$school_id];

  if ($rating) {
    $rating_string = '<div><span>Your rating:</span><br />';
    $rating_string .= '<span class="dm-rating">';
    for ($i=1; $i <= $rating; $i++) {
      $rating_string .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
    }
    $rating_string .= '</span></div>';
  } else {
    $rating_string = '';
  }

  return $rating_string;

}

  public function get_ratings_block() {
    $ratings_block = '<span class="dm-rating">';
    for ($i=1; $i < 6; $i++) {
      $ratings_block .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
    }

    $ratings_block .= '</span>';

    return $ratings_block;
  }




} // End Class


function dm_filter_school_content( $content ) {

  $dm_template_elements = new DM_TemplateElements;

  $custom_content = $dm_template_elements -> dm_get_school_actions_block( $content );

  $custom_content .= $content;
  return $custom_content;
}


add_filter( 'the_content', 'dm_filter_school_content', 10, 10 );

 ?>