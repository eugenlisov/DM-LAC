<?php/*
Plugin Name: DevMaverick Surveys
Description: Displays the survey data on the site
Version:     1.0
Author:      Eugen Lisov
Author URI:  http://devmaverick.com/
*/



require 'admin/admin-setup.php';            // The Main template setup page// require 'admin/admin-settings.php';         // Set up the plugin WP settings, such as leads name, etc// Require the neccesary functions filesrequire 'functions/assets.php';require 'functions/ajax-functions.php';require 'functions/meta-boxes.php';
require 'functions/functions-all.php';
require 'functions/deprecated.php';

// require 'functions/functions-admin.php'; // Functions that will only be used in the admin section of this plugin


// Scheduled cron jobs
// require 'functions/cron.php';


// Require classes
require_once('classes/classAjax.inc');
require_once('classes/classCallToAction.inc');
require_once('classes/classChart.inc');
require_once('classes/classCheckout.inc');
require_once('classes/classComparator.inc');
require_once('classes/classComparatorPDF.inc');
require_once('classes/classConstants.inc');
require_once('classes/classCustomPostColumns.inc');
require_once('classes/classDashboard.inc');
require_once('classes/classGetPremium.inc');
require_once('classes/classMetaBox.inc');
require_once('classes/classMMProtection.inc');
require_once('classes/classQuestion.inc');
require_once('classes/classResponse.inc');
require_once('classes/classSatisfactionScore.inc');
require_once('classes/classSchool.inc');
require_once('classes/classSetup.inc');
require_once('classes/classShortcodes.inc');
require_once('classes/classTabsSection.inc');
require_once('classes/classTab.inc');
require_once('classes/classTemplateElements.inc');
require_once('classes/classNPS.inc');
require_once('classes/classMyColleges.inc');
require_once('classes/classVSL.inc');


require_once('classes/classCollegeActions.inc');
require_once('classes/classModal.inc');
require_once('classes/classNote.inc');
require_once('classes/classRating.inc');

// Include the main TCPDF library (search for installation path).
require_once('lib/tcpdf/tcpdf.php');

if ( is_admin() ) {
  require_once('classes/classImportSurveys.inc');
  require_once('classes/classImportFastFacts.inc');
}
