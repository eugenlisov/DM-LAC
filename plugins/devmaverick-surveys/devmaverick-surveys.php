<?php/*
Plugin Name: DevMaverick Surveys
Description: Displays the survey data on the site
Version:     1.0
Author:      Eugen Lisov
Author URI:  http://devmaverick.com/
*/



require 'admin/admin-setup.php';            // The Main template setup page// require 'admin/admin-settings.php';         // Set up the plugin WP settings, such as leads name, etc// Require the neccesary functions filesrequire 'functions/ajax-functions.php';require 'functions/meta-boxes.php';
require 'functions/functions-all.php';
require 'functions/register-shortcodes.php';
require 'functions/constants.php';
require 'functions/deprecated.php';

// require 'functions/functions-admin.php'; // Functions that will only be used in the admin section of this plugin


// Scheduled cron jobs
// require 'functions/cron.php';


// Require classes
require_once('classes/classChart.inc');
require_once('classes/classMetaBox.inc');
require_once('classes/classQuestion.inc');
require_once('classes/classSchool.inc');
require_once('classes/classTabsSection.inc');
require_once('classes/classTab.inc');
require_once('classes/classTemplateElements.inc');
require_once('classes/classNPS.inc');






// Enqueue scripts


wp_enqueue_style('dm-tabs', plugins_url() . '/devmaverick-surveys/assets/css/dm-custom.css');
wp_enqueue_script('dm-all-site-scripts',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-scripts.js', 'simple-locator', '1.0.0', true);

//Enqueue the script that's open the modal after ading to favorites
wp_enqueue_script('dm-favorites',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-favorites.js', 'simple-locator', '1.0.0', true);
wp_enqueue_script('dm-colleges',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-colleges.js', array(), '1.0.0', true);

// if (is_page( PAGE_GET_PREMIUM )) {
  wp_enqueue_script('dm-landing-page',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-landing-page.js', array(), '1.0.0', true);
// }


wp_enqueue_style('dm-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
