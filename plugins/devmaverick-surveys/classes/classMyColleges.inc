<?php
/**
 * Use like this: $dm_my_colleges = new DM_MyColleges;
 */

class DM_MyColleges {

  public function my_colleges_list() {

    $user_id = get_current_user_id();
    $favorites = unserialize( CURRENT_USER_FAVORITE_SCHOOLS );

    // echo '<pre>';
    // print_r(CURRENT_USER_FAVORITE_SCHOOLS);
    // echo '</pre>';

    $this -> update_my_college_notes();

    $favorites_count = count($favorites);

    $return = '';


    $return .= '<p><strong>Total Colleges in Your Watch List:</strong> ' . $favorites_count . '</p>';

    $return .= '<form action="" method="POST">
                  <div class="dm-my-coleges-list">';

    if ($favorites_count > 0) {
      foreach ($favorites as $key => $college_id) {

        $return .= $this -> my_college_item( $college_id );

      }
    }

    $return .= '  </div>
                <input type="submit" value="Save notes">';
    $return .= '</form>';

    return $return;

  }

  public function my_colleges_widget() {

    $user_id = get_current_user_id();
  	$favorites = unserialize( CURRENT_USER_FAVORITE_SCHOOLS );
  	$school_ratings 	= get_user_meta( $user_id, 'dm_user_ratings', true );


    $favorites_count = count($favorites);
    if ( $favorites_count == 0 ) {
      return false;
    }


  	$return = '<div class="widget lazur-bg p-xl dm-my-colleges-widget">

                                <h2>
                                    My Colleges
                                </h2>
                        <ul class="list-unstyled m-t-md">';


                        foreach ($favorites as $key => $college_id) {


                          $college_name 			= get_the_title( $college_id );
                          $college_permalink 	= get_the_permalink( $college_id );


                          // $thumb = get_the_post_thumbnail( $college_id, 'thumbnail' );
                          //
                          // echo '<pre>';
                          // print_r($thumb);
                          // echo '</pre>';


                          $return .= '<li class="college-item">';

                          $return .= '<a href=" ' . $college_permalink . '">' . $college_name . '</a><br />';

                          $return .= $this -> star_rating_block_widget( $college_id );

                          $return .= '</li>';



                          $return .= $this -> star_rating_block_widget();

                    		};


          $return .= '  </ul>

                    </div>
                    <div class="dm-my-collges-widget">';

  	$return .= '</div>';

    return $return;
  }

  public function update_my_college_notes() {

    $post_notes = $_POST;

    if ($post_notes) {

      $notes_array = [];

      foreach ($post_notes as $key_note => $note) {

        $note = trim($note);
        $fav_college_id = str_replace("note-","",$key_note);
        $notes_array[$fav_college_id] = $note;

      }
      update_user_meta( $user_id, 'dm_user_notes', $notes_array );

    }

  }

  private function my_college_item( $college_id ) {

    if ( ! $college_id ) {
      return false;
    }
    // $background = $this -> random_background();
    $background = 'gray-bg';

    $thumb = get_the_post_thumbnail_url( $college_id, 'thumbnail' );
    $thumb_block = '';
    if ( $thumb ) {
      $thumb_block = '<img src="' . $thumb . '" class="img-circle circle-border m-b-md pull-right" alt="profile">';
    }

    $return = '<div class="col-lg-6">
        <div class="widget ' . $background . ' no-padding">
            <div class="p-m">
                ' . $thumb_block . '
                <h1 class="m-xs">' . get_the_title( $college_id ) . '</h1>

                ' . $this ->  star_rating_block( $college_id ) . '



                ' . $this -> tabs_block ( $college_id ) . '



                <p><a href="' . get_the_permalink( $college_id ) . '">View details for ' . get_the_title( $college_id ) . '</a></p>
            </div>


        </div>
    </div>';


    return $return;



  }


  private function random_background() {

      $available_backgrounds = array( 'gray-bg', 'blue-bg', 'navy-bg', 'lazur-bg', 'yellow-bg', 'red-bg', 'black-bg' );

      return $available_backgrounds[array_rand( $available_backgrounds, 1 )];
  }

  private function star_rating_block( $college_id ) {

    if ( ! $college_id ) {
      return false;
    }

    $rating = $this -> get_my_college_rating ( $college_id );

    $return = '<div class="m-b-md">';

    for ($i=1; $i <= $rating ; $i++) {
      $return .= '<i class="fa fa-star fa-2x"></i>';
    }

    $return .= '</div>';

    return $return;

  }
  private function star_rating_block_widget( $college_id = '' ) {

    if ( ! $college_id ) {
      return false;
    }

    $rating = $this -> get_my_college_rating ( $college_id );

    $return = '';

    for ($i=1; $i <= $rating ; $i++) {
      $return .= '<i class="fa fa-star fa-1x"></i>';
    }



    return $return;

  }

  private function nps_block( $college_id ) {
    $dm_school = new DM_School;
    $nps = $dm_school -> get_school_nps ( $college_id );

    if ( $nps ) {

    $return = '<h2 class="font-bold no-margins">
                  Net Promoter Score: <span class="net-promoter">' . $nps . '</span>
              </h2>';

    }
    return $return;
  }

  private function tabs_block ( $college_id ) {


      $return = '<ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#menu-' . $college_id . '-1"><i class="fa fa-meh-o" aria-hidden="true"></i> NPS</a></li>
                  <li><a data-toggle="tab" href="#home-' . $college_id . '"><i class="fa fa-sticky-note" aria-hidden="true"></i> Your Notes</a></li>
                  <li><a data-toggle="tab" href="#menu-' . $college_id . '-2"><i class="fa fa-comment" aria-hidden="true"> </i>Student Reviews</a></li>
                  <li><a data-toggle="tab" href="#menu-' . $college_id . '-3"><i class="fa fa-address-card" aria-hidden="true"></i> Contact</a></li>
                </ul>

                <div class="tab-content">
                  <div id="menu-' . $college_id . '-1" class="tab-pane fade in active">
                    <h3>Menu 1</h3>
                    ' . $this -> nps_block( $college_id ) . '
                  </div>
                  <div id="home-' . $college_id . '" class="tab-pane fade">
                      <h3 class="font-bold no-margins">
                          Your notes:
                      </h3>
                      <textarea rows="2" cols="50" name="note-' . $college_id . '">' . $this -> get_my_college_note ( $college_id ) . '</textarea>
                  </div>

                  <div id="menu-' . $college_id . '-2" class="tab-pane fade">
                    <h3>Menu 2</h3>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
                  </div>
                  <div id="menu-' . $college_id . '-3" class="tab-pane fade">
                    <h3>Contact Information</h3>
                    ' . $this -> contact_data_block( $college_id ) . '
                  </div>
                </div>';

      return $return;
  }

  public function contact_data_block( $college_id = '' ) {

    if ( ! $college_id ) {
      return false;
    }

    $contact_data  = get_school_contact_data ( $college_id );

    // echo '<pre>';
    // print_r ( $contact_data );
    // echo '</pre>';

    if  ( $contact_data ) {

        $return = '<table style="width:100%">';

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
                        $return .= '
                                <tr>
                                  <td class="contact-label"><strong>' . $contact_label . ': </strong></td>
                                  <td class="contact-item">' . $contact_item . '</td>
                                </tr>';
                      }
                    }


        $return .= '
            </table>';

    }

    return $return;
  }

  public function get_my_college_rating ( $college_id ) {

    if ( ! $college_id ) {
      return false;
    }

    $current_user_school_ratings 	= unserialize( CURRENT_USER_SCHOOL_RATINGS );


    if ( $current_user_school_ratings[$college_id] ) {
      return $current_user_school_ratings[$college_id];
    } else {
      return false;
    }



  }


  public function get_my_college_note ( $college_id ) {

    if ( ! $college_id ) {
      return false;
    }

    $current_user_notes 	= unserialize( CURRENT_USER_SAVED_NOTES );


    if ( $current_user_notes[$college_id] ) {
      return $current_user_notes[$college_id];
    } else {
      return false;
    }



  }


} // End class
