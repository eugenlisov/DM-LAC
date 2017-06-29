<?php
/**
 * Use like this: $dm_my_colleges = new DM_MyColleges;
 */

class DM_MyColleges {

  public function my_colleges_page() {

    $user_id = get_current_user_id();
    $favorites = unserialize( CURRENT_USER_FAVORITE_SCHOOLS );

    // echo '<pre>';
    // print_r( $favorites );
    // echo '</pre>';

    if ( $favorites ) {
      $return = $this -> my_colleges_list( $favorites );
    } else {
      $return = $this -> my_colleges_empty();
    }


    return $return;


    // Asta nu este necesar aici. O sa zboare.
    $this -> update_my_college_notes();

  }

  public function my_colleges_list( $favorites = '' ) {
    if ( ! $favorites ) {
      $favorites = unserialize( CURRENT_USER_FAVORITE_SCHOOLS );
    }

    $user_id = get_current_user_id();
    $favorites_count = count($favorites);

    switch ($favorites_count) {
      case 0:
        $message = '<h3>There are no Colleges in Your Watch List:</h3>';
        break;
      case 1:
        $message = '<h3>There is one College in Your Watch List:</h3>';
        break;
      default:
        $message = '<h3>There are ' . $favorites_count . ' Colleges in Your Watch List:</h3>';
        break;
    }

    $return .= $message;

    $return .= '<div class="dm-my-coleges-list" user-id="' . $user_id . '">';

    if ($favorites_count > 0) {
      foreach ($favorites as $key => $college_id) {

        $return .= $this -> my_college_item( $college_id );

      }
    }

    $return .= '  </div>';

    return $return;


  }
  public function my_colleges_empty() {

    $return = '<div class="dm-my-colleges-empty">
                <h2>Looks like you haven\'t added any college to your watch list!</h2>
                  <h4>Why wait?</h4>
                  <p>Take avantage of the features you have as a Liberal Arts Colleges member by going through our list of over 170 schools and checking the ones you want to watch</p>
                  <h3>Don\'t know where to start?</h3>
                  <p>Here\'s a hint: </p>
                  <div class="dm-my-colleges-option-links">
                    <div class="col-md-6">
                      <a href="' . get_permalink( PAGE_SCHOOL_COMPARISONS ) . '" class="btn btn-lg btn-success btn-my-colleges">School Comparisons</a>
                    </div>
                    <div class="col-md-6">
                      <a href="' . get_permalink( PAGE_SCHOOL_RANKINGS ) . '" class="btn btn-lg btn-success btn-my-colleges">College Rankings</a>
                    </div>

                  </div>
                </div>';

    return $return;

    // echo 'Looks like there is no college for you here!';
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

    $dm_rating = new DM_Rating;

    $dm_school = new DM_School;
    $selectivity = $dm_school -> get_school_selectivity( $college_id );
    $selectivity_block = ( $selectivity ) ? '<div class="dm-my-college-meta-item">Selectivity: ' .  $selectivity . '</div>' : '';

    $location = $dm_school -> get_school_location( $college_id );
    $location_block = ( $selectivity ) ? '<div class="dm-my-college-meta-item">Location: ' .  $location . '</div>' : '';


    $return = '<div class="col-lg-6 dm-my-colege-item" school-id="' . $college_id . '">
        <div class="widget ' . $background . ' no-padding">
            <div class="p-m">
                ' . $thumb_block . '
                <h1 class="m-xs"><a href="'. get_permalink( $college_id ) . '">' . get_the_title( $college_id ) . '</a></h1>
                <div class="dm-my-colege-meta">
                  ' . $location_block . '
                  ' . $selectivity_block . '
                  ' . $dm_rating ->  get_my_college_rating_block( $college_id ) . '
                </div>
                  ' . $this -> tabs_block ( $college_id ) . '


                <div class="dm-my-college-bottom-actions">
                  <a href="' . get_the_permalink( $college_id ) . '">View ' . get_the_title( $college_id ) . ' page</a>
                </div>
            </div>


        </div>
    </div>';


    return $return;



  }


  private function random_background() {

      $available_backgrounds = array( 'gray-bg', 'blue-bg', 'navy-bg', 'lazur-bg', 'yellow-bg', 'red-bg', 'black-bg' );

      return $available_backgrounds[array_rand( $available_backgrounds, 1 )];
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

  public function nps_block( $college_id ) {
    $dm_school = new DM_School;
    $nps = $dm_school -> get_school_nps ( $college_id );

    if ( $nps ) {
      $dm_nps = new DM_NPS;

    $return =  '<h3 class="font-bold no-margins">';
    // $return .= '  Net Promoter Score: <span class="net-promoter">' . $nps . '</span>';
    $return .= '</h3>';
    $return .= $dm_nps -> nps_traffic_lights( $nps, $college_id );

    }
    return $return;
  }
  private function student_reviews_block( $college_id = '' ) {
    if ( ! $college_id ) {
      return false;
    }

    $live_open_responses_by_question = get_post_meta( $college_id, 'school_open_ended_responses', true);

    if ( $live_open_responses_by_question[4] ) {
      $student_reviews = $live_open_responses_by_question[4];
      $random_review = $student_reviews[array_rand( $student_reviews )];

      $review_block = '<div class="dm-random-student-review">
                          <div class="col-md-8 dm-review-body">
                            <i class="fa fa-quote-left fa-2x" aria-hidden="true"></i>
                            ' . $random_review['response_text'] . '
                          </div>
                          <div class="col-md-4 dm-review-graduation">
                            <div>
                              <h4>Graduation Class</h4>
                              <p>' . $random_review['graduation_year'] . '</p>
                            </div>
                          </div>
                       </div>';

      $reviews = '<h4>Here\'s what students like best about this school:</h4>';

      $reviews .= $review_block;

      return $reviews;

    }



    // $reviews = '<h4>Here\'s what students like best about this school:</h4>
    //             <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>';
    //
    // return $reviews;
  }


  private function tabs_block ( $college_id ) {



      $dm_school = new DM_School;
      $dm_note = new DM_Note;


      $nps = $dm_school -> get_school_nps ( $college_id );

      $return = '<div class="dm-my-college-tabs">
                  <ul class="nav nav-tabs">';

      if  ( $nps ) {
        // if ( $nps >= 41 ) {
        //   $smiley_font = 'fa-smile-o';
        // } elseif ( $nps >= 30 && $nps < 40 ) {
        //   $smiley_font = 'fa-meh-o';
        // } else {
        //   $smiley_font = 'fa-frown-o';
        // }
        $return .= ' <li class="active"><a data-toggle="tab" href="#menu-' . $college_id . '-1">Recommendation</a></li>';
      } else {
        $second_tab_active = 'active';
        $second_tab_active_content = 'in active';
      }
      $return .= '<li class="' . $second_tab_active . '"><a data-toggle="tab" href="#home-' . $college_id . '"><i class="fa fa-sticky-note" aria-hidden="true"></i> Your Notes</a></li>';

      $live_open_responses_by_question = get_post_meta( $college_id, 'school_open_ended_responses', true);
      if ( $live_open_responses_by_question[4] ) {
        $return .= '<li><a data-toggle="tab" href="#menu-' . $college_id . '-2"><i class="fa fa-comment" aria-hidden="true"> </i> Student Reviews</a></li>';
      }

      $return .= '<li><a data-toggle="tab" href="#menu-' . $college_id . '-3"><i class="fa fa-address-card" aria-hidden="true"></i> Contact</a></li>';
      $return .= '</ul>';

      $return .= '<div class="tab-content">';

      if  ( $nps ) {
        $return .= '<div id="menu-' . $college_id . '-1" class="tab-pane dm-tab-nps fade in active">
                      ' . $this -> nps_block( $college_id ) . '
                    </div>';
      }
      $return .= '<div id="home-' . $college_id . '" class="tab-pane dm-tab-notes fade ' . $second_tab_active_content . '">
                      ' . $dm_note -> my_college_note_block( $college_id ) . '
                  </div>';

      if ( $live_open_responses_by_question[4] ) {
        $return .= '<div id="menu-' . $college_id . '-2" class="tab-pane fade">
                      '  . $this -> student_reviews_block( $college_id ) . '
                    </div>';
      }

      $return .= '<div id="menu-' . $college_id . '-3" class="tab-pane fade">
                    <h3>Contact Information</h3>
                    ' . $this -> contact_data_block( $college_id ) . '
                  </div>';

      $return .= '</div>
                </div>';

      return $return;
  }

  public function contact_data_block( $college_id = '' ) {

    if ( ! $college_id ) {
      return false;
    }
    $dm_school = new DM_School;

    $contact_data =  $dm_school -> get_contact_data ( $college_id ) ;



    // echo '<pre>';
    // print_r ( $contact_data );
    // echo '</pre>';

    if  ( $contact_data ) {

        $return = '<table class="dm-my-college-contact-info-table">';

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

  // NOTE Duplicate. Already exists similarly in classRating
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

  // NOTE Duplicate. Already exists similarly in classNote
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
