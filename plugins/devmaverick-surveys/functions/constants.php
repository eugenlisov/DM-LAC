<?php

add_action('init', 'dm_partner_define_constants');

function dm_partner_define_constants(){

  // Core pages
  define( 'PAGE_MY_COLLEGES', 4199 );
  define( 'PAGE_MY_ACCOUNT', 4196 );
  define( 'PAGE_PREMIUM_HOME', 4187 );
  define( 'PAGE_GET_PREMIUM', 4205 );
  // define( 'PAGE_PREMIUM_HOME', 4187 );
  // define( 'PAGE_PREMIUM_HOME', 4187 );
  // define( 'PAGE_PREMIUM_HOME', 4187 );
  // define( 'PAGE_PREMIUM_HOME', 4187 );

  // Misc pages
  define( 'LOG_IN_PAGE', 4190 );
  define( 'LOG_OUT_PAGE', 4191 );
  define( 'CONTACT_PAGE', 186 );


  // Dynamic data

  if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $current_user_notes 				= get_user_meta( $user_id, 'dm_user_notes', true );
    $current_user_school_ratings 	= get_user_meta( $user_id, 'dm_user_ratings', true );
    $favorites = get_user_meta($user_id, 'simplefavorites');
    $favorites = $favorites[0][0]['posts'];

    define( 'CURRENT_USER_SAVED_NOTES', serialize( $current_user_notes ) );
    define( 'CURRENT_USER_SCHOOL_RATINGS', serialize( $current_user_school_ratings ) );
    define( 'CURRENT_USER_FAVORITE_SCHOOLS', serialize( $favorites ) );
  }
}


 ?>
