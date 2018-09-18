<?php
/**
 * This file adds the Landing template to the News Pro Theme.
 *
 * @author StudioPress
 * @package News Pro
 * @subpackage Customizations
 */

/*
Template Name: MM Home Redirect
*/

$membership_id = \GFLead\Services\Membership::membership_level_id();
echo 'Current memebrship ID: ' . $membership_id;

switch ( $membership_id ) {
    case 3:
            echo 'Redirecting to ' . get_permalink( 4656 );
            // wp_redirect( get_permalink( 4656 ) ); // Free Home
            exit;
        break;
    case 2:
            echo 'Redirecting to ' . get_permalink( 4185 );
            // wp_redirect( get_permalink( 4185 ) ); // LAC Insiders Home
            exit;
        break;

    default:
        # code...
        break;
}


// if ( $membership_id != 2 && ! current_user_can( 'administrator' ) ) {
//     return;
// }
