<?php
class DM_Constants {


  /**
   * Constructor
   */
  public function __construct() {

      add_action('init', array( $this, 'define_constants' ) );

  }

  public function define_constants(){

    // Core pages
    define( 'PAGE_MY_COLLEGES', 4400 );
    define( 'PAGE_COMPARE_COLLEGES', 4434 );
    define( 'PAGE_MY_ACCOUNT', 4194 );
    define( 'PAGE_PREMIUM_HOME', 4185 );
    define( 'PAGE_GET_PREMIUM', 4401 );
    define( 'PAGE_GET_PREMIUM_PERMALINK', get_the_permalink( PAGE_GET_PREMIUM ) . '?rid=pGQZ48' );
    define( 'PAGE_SIGNUP', 4234 ); // ???
    define( 'PAGE_PREMIUM_VSL1', 4401 ); // Same as PAGE_GET_PREMIUM
    define( 'PAGE_PREMIUM_VSL2', 4498 );
    define( 'PAGE_PREMIUM_VSL3', 4537 );
    define( 'PAGE_PRICING', 4502 );
    define( 'PAGE_MAP', 3565 );

    // define( 'PAGE_PREMIUM_HOME', 4187 );
    // define( 'PAGE_PREMIUM_HOME', 4187 );
    // define( 'PAGE_PREMIUM_HOME', 4187 );
    // define( 'PAGE_PREMIUM_HOME', 4187 );

    // Misc pages
    define( 'LOG_IN_PAGE', 4188 );
    define( 'LOG_OUT_PAGE', 4189 );
    define( 'CONTACT_PAGE', 186 );

    // Content pages
    define( 'PAGE_FEATURED_SCHOOLS', 2854 );
    define( 'PAGE_SCHOOL_RANKINGS', 3096 );
    define( 'PAGE_SCHOOL_COMPARISONS', 3142 );
    define( 'PAGE_VOICE_OF_STUDENT', 4442 );


    // URL Locations
    define( SURVEYS_PLUGIN_FOLDER, 'devmaverick-surveys' );
    define( SURVEYS_PLUGIN_FOLDER_URL, plugins_url() . '/' . SURVEYS_PLUGIN_FOLDER );
    define( SURVEYS_ASSETS_URL, SURVEYS_PLUGIN_FOLDER_URL . '/assets');



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

} // End class

$dm_constants = new DM_Constants();
