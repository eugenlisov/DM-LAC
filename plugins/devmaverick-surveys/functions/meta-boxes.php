 <?php
///////////////// Metabox

/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'school-contact-data-meta-box', __( 'School Contact Data', 'textdomain' ), 'dm_school_contact_data_meta_box', 'schools' );
    add_meta_box( 'meta-box-id', __( 'Featured Open Ended Questions', 'textdomain' ), 'dm_open_ended_questions_meta_box', 'schools' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function dm_open_ended_questions_meta_box( $post ) {

    $dm_meta_box = new DM_MetaBox;
    echo $dm_meta_box -> get_open_ended_questions_meta_box( $post );
    
}

function dm_school_contact_data_meta_box() {
  global $post;
  $post_id =  $post->ID;

  $contact_data  = get_school_contact_data ( $school_id );

  ?>
  This information can only be edited in the CSV file that it improted (v1)
  <table style="width:100%">
  <?php
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
    echo  '
            <tr>
              <td><strong>' . $contact_label . ': </strong></td>
              <td>' . $contact_item . '</td>
            </tr>';
    }
  }

  ?>

  </table>




  <?php



}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wpdocs_save_meta_box' );

?>
