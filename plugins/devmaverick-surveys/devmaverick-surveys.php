<?php/*
Plugin Name: DevMaverick Surveys
Description: Displays the survey data on the site
Version:     1.0
Author:      Eugen Lisov
Author URI:  http://devmaverick.com/
*/



require 'admin/admin-setup.php';            // The Main template setup page// require 'admin/admin-settings.php';         // Set up the plugin WP settings, such as leads name, etc// Require the neccesary functions filesrequire 'functions/assets.php';require 'functions/ajax-functions.php';require 'functions/meta-boxes.php';
require 'functions/functions-all.php';
require 'functions/register-shortcodes.php';
require 'functions/constants.php';
require 'functions/deprecated.php';

// require 'functions/functions-admin.php'; // Functions that will only be used in the admin section of this plugin


// Scheduled cron jobs
// require 'functions/cron.php';


// Require classes
require_once('classes/classCallToAction.inc');
require_once('classes/classChart.inc');
require_once('classes/classCustomPostColumns.inc');
require_once('classes/classDashboard.inc');
require_once('classes/classMetaBox.inc');
require_once('classes/classMMProtection.inc');
require_once('classes/classQuestion.inc');
require_once('classes/classSchool.inc');
require_once('classes/classTabsSection.inc');
require_once('classes/classTab.inc');
require_once('classes/classTemplateElements.inc');
require_once('classes/classNPS.inc');
require_once('classes/classMyColleges.inc');

require_once('classes/classCollegeActions.inc');
require_once('classes/classModal.inc');
require_once('classes/classNote.inc');
require_once('classes/classRating.inc');
