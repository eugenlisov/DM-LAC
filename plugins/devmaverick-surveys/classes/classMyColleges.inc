<?php
/**
 * Use like this: $dm_my_colleges = new DM_MyColleges;
 */

class DM_MyColleges {

  public function my_colleges_list() {

    $user_id = get_current_user_id();
    $favorites = get_user_meta($user_id, 'simplefavorites');
    $favorites = $favorites[0][0]['posts'];

    // echo '<pre>';
    // print_r($favorites);
    // echo '</pre>';

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

    $saved_user_notes 				= get_user_meta( $user_id, 'dm_user_notes', true );
    $existing_school_ratings 	= get_user_meta( $user_id, 'dm_user_ratings', true );

    $favorites_count = count($favorites);

    $return_string = '';


    $return_string .= '<p><strong>Total Colleges in My List:</strong> ' . $favorites_count . '</p>';

    $return_string .= '<form action="" method="POST">';

    if ($favorites_count > 0) {
      foreach ($favorites as $key => $college_id) {

        $return_string .= '<div class="row">';
        $return_string .= '	<div class="col-md-4">';
        $return_string .= '		<p><a href="' . get_the_permalink( $college_id ) . '">' . get_the_title( $college_id ) . '</a></p>';
        $return_string .= '	</div>';

        // $image      = get_the_post_thumbnail( $college_id );
        // $image_url  = wp_get_attachment_image_src( $image, 'full', false )[0];
        //
        // echo '<pre>';
        // print_r($image);
        // echo '</pre>';
        // $return_string .= $image_url;

        $return_string .= '	<div class="col-md-8 my-colleges-note">';



            $rating = $existing_school_ratings[ $college_id ];
            if ($rating != '') {
              $return_string .= '	<p>';
              $return_string .= '<strong>Your rating: ' . $rating . '</strong>';
              $return_string .= '<span class="dm-rating">';
              for ($i=1; $i <= $rating; $i++) {
                $return_string .= '<i class="fa fa-star" aria-hidden="true" rating="' . $i . '"></i>';
              }
              $return_string .= '</span>';
              $return_string .= '	</p>';
            }


        $return_string .= '		<p>Note:</p>';
        $return_string .= '		<textarea rows="2" cols="50" name="note-' . $college_id . '">' . $saved_user_notes[$college_id] . '</textarea>';
        $return_string .= '		<br /><br />';
        $return_string .= '	</div>';
        $return_string .= '</div>';

      }
    }

    $return_string .= '<input type="submit" value="Save notes">';
    $return_string .= '</form>';

    return $return_string;
    
  }


}
