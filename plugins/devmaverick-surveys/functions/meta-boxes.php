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
* Save post metadata when a post is saved.
*
* @param int $post_id The post ID.
* @param post $post The post object.
* @param bool $update Whether this is an existing post being updated or not.
*/
function save_contact_data_meta( $post_id, $post, $update ) {

   /*
    * In production code, $slug should be set only once in the plugin,
    * preferably as a class property, rather than in each function that needs it.
    */
   $post_type = get_post_type($post_id);

   // If this isn't a 'book' post, don't update it.
   if ( "schools" != $post_type ) return;

   // - Update the post's metadata.

   if ( isset( $_POST['contact_full_name'] ) ) {
     $contact_full_name = sanitize_text_field( $_POST['contact_full_name'] );
   }
   if ( isset( $_POST['contact_title'] ) ) {
     $contact_title = sanitize_text_field( $_POST['contact_title'] );
   }
   if ( isset( $_POST['contact_phone'] ) ) {
     $contact_phone = sanitize_text_field( $_POST['contact_phone'] );
   }
   if ( isset( $_POST['contact_email'] ) ) {
     $contact_email = sanitize_text_field( $_POST['contact_email'] );
   }

   // 1. Get the highriseID from this school
   $current_highrise_id = get_post_meta($post_id, 'school_highrise_id', true);
   $school_name = get_the_title( $post_id );

   global $wpdb;

   $check_highrise_id_sql = 'SELECT * FROM dm_school_contacts WHERE highrise_id = "' . $current_highrise_id . '" ';
   $check_highrise_id = $wpdb -> get_row( $check_highrise_id_sql );


   $sql_insert = 'INSERT INTO dm_school_contacts (highrise_id, full_name, school_name, title, phone, email)
                   VALUES ("' . $current_highrise_id . '", "' . $contact_full_name . '", "' . $school_name . '", "' . $contact_title . '", "' . $contact_phone . '", "' . $contact_email . '")';

   $sql_update = 'UPDATE  dm_school_contacts
                   SET highrise_id = "' . $current_highrise_id . '", full_name = "' . $contact_full_name . '", school_name = "' . $school_name . '", title = "' . $contact_title . '", phone = "' . $contact_phone . '", email = "' . $contact_email . '"
                   WHERE highrise_id = "' . $current_highrise_id . '"';

   if ( empty ( $check_highrise_id ) ) {
     $sql = $sql_insert;
   } else {
     $sql = $sql_update;
   }

   $wpdb->query($sql);

}
add_action( 'save_post', 'save_contact_data_meta', 10, 3 );
