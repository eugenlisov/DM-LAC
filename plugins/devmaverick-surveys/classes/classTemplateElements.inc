<?php
/**
 * Use like this $dm_template_elements = new DM_TemplateElements;
 */
class DM_TemplateElements {


  public function dm_get_school_actions_block() {

      if (is_singular( 'schools' )) {

          // wp_enqueue_style( 'dm-inspinia' );
          wp_enqueue_style( 'dm-inspinia-light' );

          $dm_modal = new DM_Modal;

          if (is_user_logged_in()) {
            $content = $this -> get_logged_in_content();
            $modal   = $dm_modal -> college_rating_modal();
            $modal   .= $dm_modal -> college_note_modal();
          } else {
            $content = $this -> get_logged_out_content();
            $modal   = $this -> get_logged_out_modal();
          }


          // $contact_modal  = $dm_modal -> college_contact_modal();
          $return = $content . $modal . $contact_modal;

          return $return;

        }

  }

  // DEPRECATED
  // replaced with  college_contact_modal in class Modals.
  public function get_school_contact_modal() {
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

  public function get_logged_out_modal() {

    $return = '';

    $return .= '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="dm-login-upgrade-modal">
      <div class="vertical-alignment-helper">
        <div class="modal-dialog modal-md vertical-align-center" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h2 class="modal-title" id="mySmallModalLabel">Log in or Upgrade</h2>
            </div>
            <div class="modal-body" style="text-align: center; padding-top: 0 !important;">
              <p>To make use of this feature, you need to be a member.</p>
              <p>
                <button target="' . get_permalink( LOG_IN_PAGE ) . '" class="dm-btn dm-btn-md dm-btn-green trigger-redirect-to-page">Log In</button>
                <button  class="dm-btn dm-btn-md dm-btn-yellow trigger-close-modal"  data-leadbox-popup="1454f8773f72a2:1410ced42b46dc" data-leadbox-domain="jsdpub.lpusercontent.com" >Learn More</button>

              </p>
            </div>
          </div>
        </div>
      </div>
    </div>';

    return $return;
  }

  // DEPRECATED
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
              <h2 class="modal-title" id="mySmallModalLabel">Add a note about<br /> ' . get_the_title() . '</h2>
            </div>
            <div class="modal-body">

              <p><textarea id="dm-single-note" rows="4" cols="50" user-id="' . $user_id . '" school-id="' . $school_id . '" placeholder="What struck you most about ' . get_the_title() . '? Add it here so you remember later.">' . $user_note . '</textarea></p>
              <p><button id="dm-single-note-button" type="button" class="dm-btn dm-btn-lg dm-btn-green trigger-save-note">Save Note</button></p>
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

      // Prevent seeing this by Quiz Users
      $membership_id = \GFLead\Services\Membership::membership_level_id();
      // echo 'Current memebrship ID: ' . $membership_id;


      if ( $membership_id != 2 && ! current_user_can( 'administrator' ) ) {
          return;
      }
      // End  Prevent seeing this by Quiz Users

      $user_id = get_current_user_id();
      $school_id = get_the_ID();
      $user_note = get_user_meta( $user_id, 'dm_user_notes', true )[$school_id];

      $rating_string = $this -> get_my_rating_block();

      $dm_school = new DM_School;
      $my_colleges_widget = $dm_school -> dm_my_colleges_widget( 10 );
      $my_colleges_count  = $dm_school -> count_my_colleges();

    $my_colleges = new DM_MyColleges;
    $rating = $my_colleges -> get_my_college_rating ( $school_id );

    $return = '<div class="row dm-college-top-content logged-in" school-id="' . get_the_ID() . '" user-id="' . $user_id . '">

                  <div class="col-xs-6 col-lg-4 dm-go-to-survey-results trigger-go-to-survey-results">

                      <div class="widget style1 blue-bg">
                          <div class="row">
                              <div class="col-xs-3">
                                  <i class="fa fa-bar-chart fa-2x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                  <span>Take me to Survey Results</span>
                              </div>
                          </div>
                      </div>

                  </div>

                  <div class="col-xs-6 col-lg-4 dm-my-colleges">
                      ' . do_shortcode('[favorite_button]') . '
                  </div>';

                  $user_favorites = unserialize( CURRENT_USER_FAVORITE_SCHOOLS );

                  $dm_college_actions = new DM_CollegeActions;

                  if ( $user_favorites ) {
                    if ( in_array( $school_id, $user_favorites ) ) {

                      $return .= $dm_college_actions -> button_school_rating( $school_id, $user_id );
                      $return .= $dm_college_actions -> button_school_note();

                    }
                  }

                  $return .= '</div>';

      // return $logged_in_content . $return;
      return $return;

  }




  public function get_logged_out_content() {

    $return = '<div class="row dm-college-top-content logged-out" school-id="' . get_the_ID() . '" user-id="' . $user_id . '">



                <div class="col-xs-6 col-lg-4 dm-go-to-survey-results trigger-go-to-survey-results">

                    <div class="widget style1 blue-bg">
                        <div class="row">
                            <div class="col-xs-4 dm-icon-column">
                                <i class="fa fa-bar-chart fa-3x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span>Take me to Survey Results</span>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-xs-6 col-lg-4 dm-add-favorite-logged-out trigger-login-upgrade-modal">

                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4 dm-icon-column">
                                <i class="fa fa-university fa-3x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span>Add to My Colleges</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>';

    // return $logged_in_content . $return;
    return $return;

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

  // deprecated
  // replaced with get_empty_ratings_block(); inside classRating
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
