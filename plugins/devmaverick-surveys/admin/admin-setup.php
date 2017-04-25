<?php

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page(){
  $slug = 'dm-surveys';
    add_menu_page(
        __( 'DM Surveys', 'textdomain' ),
        'Surveys',
        'manage_options',
        $slug,
        'dm_surveys_admin_main',
        'dashicons-chart-line',
        6
    );
    add_submenu_page( $slug, 'Colleges', 'Colleges', 'manage_options', $slug . '-colleges', 'dm_surveys_admin_colleges');
    add_submenu_page( $slug, 'Tabs', 'Tabs', 'manage_options', $slug . '-tabs', 'dm_surveys_admin_tabs');
    add_submenu_page( $slug, 'Import Surveys', 'Import Surveys', 'manage_options', $slug . '-import-surveys', 'dm_surveys_admin_import_surveys');
    add_submenu_page( $slug, 'Import Contacts', 'Import Contacts', 'manage_options', $slug . '-import-contacts', 'dm_surveys_admin_import_contacts');
    // add_submenu_page( $slug, 'Import LAC Averages', 'Import LAC Averages', 'manage_options', $slug . '-import-averages', 'dm_surveys_admin_import_averages');
    add_submenu_page( $slug, 'Questions', 'Questions', 'manage_options', $slug . '-questions', 'dm_surveys_admin_questions');
    // add_submenu_page( $slug, 'Survey Data', 'Survey Data', 'manage_options', $slug . '-survey-data', 'dm_surveys_admin_survey_data');
      // add_submenu_page( $slug, 'Import Options', 'Import Options', 'manage_options', $slug . '-import-options', 'dm_surveys_admin_import_options');

    // dm_register_zoho_settings();
    // add_action( 'admin_init', 'dm_register_zoho_settings' );
    //
    add_submenu_page( $slug, '< > Dev page', '< > Dev page', 'manage_options', $slug . '-dev-page', 'dm_surveys_dev_page');
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );


// function dm_register_zoho_settings() {
//
//   // Customer
// 	register_setting( 'dm-zoho-group', 'dm_zoho_api_key' );
//   register_setting( 'dm-zoho-group', 'dm_customer_lead_source' );
//   register_setting( 'dm-zoho-group', 'dm_customer_lead_wait_time' );
//   register_setting( 'dm-zoho-group', 'dm_customer_quote_terms' );
//
//   register_setting( 'dm-zoho-group', 'dm_customer_second_step_id' );
//   register_setting( 'dm-zoho-group', 'dm_customer_third_step_id' );
//   register_setting( 'dm-zoho-group', 'dm_customer_quote_generated_id' );
//
//   //Partners
// }

/**
 * Display a custom menu page
 */
function dm_surveys_admin_main(){
    require 'templates/main.php';
}

function dm_surveys_admin_colleges(){
    require 'templates/colleges.php';
}
function dm_surveys_admin_tabs(){
    require 'templates/tabs.php';
}
function dm_surveys_admin_import_surveys(){
    require 'templates/import-surveys.php';
}
function dm_surveys_admin_import_contacts(){
    require 'templates/import-contacts.php';
}
// function dm_surveys_admin_import_averages(){
//     require 'templates/import-averages.php';
// }
function dm_surveys_admin_questions(){
    require 'templates/questions.php';
}
// function dm_surveys_admin_survey_data(){
//     require 'templates/survey-data.php';
// }
function dm_surveys_admin_import_options(){
    require 'templates/import-options.php';
}
function dm_surveys_dev_page(){
    require 'templates/dev-page.php';
}


//Enqueue font awesome
function fontawesome_dashboard() {
   wp_enqueue_style('fontawesome', 'http:////netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css', '', '4.5.0', 'all');
}
add_action('admin_init', 'fontawesome_dashboard');







 ?>
