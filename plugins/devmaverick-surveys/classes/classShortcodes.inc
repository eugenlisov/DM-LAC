<?php
class DM_Shortcodes {


  /**
   * Constructor
   */
  public function __construct() {
      add_action( 'init', array( $this, 'register_shortcodes' ) );
  }


  public function register_shortcodes() {


    add_shortcode('dm-my-colleges', array( $this, 'dm_my_colleges' ) );
    add_shortcode('dm-dashboard-links', array( $this, 'dm_dashboard_links' ) );
    add_shortcode('dm-dashboard-my-colleges', array( $this, 'dm_dashboard_my_colleges_widget' ) );
    add_shortcode('dm-compare-colleges', array( $this, 'dm_compare_colleges' ) );

  }

  function dm_my_colleges() {

  	$dm_my_colleges = new DM_MyColleges;
  	$return = $dm_my_colleges -> my_colleges_page();

  	return $return;

  }


  function dm_dashboard_my_colleges_widget( $limit = 999 ) {

  	$dm_my_colleges = new DM_MyColleges;
  	$return = $dm_my_colleges -> my_colleges_widget();

  	return $return;

  }



  function dm_dashboard_links() {

  	 $dm_dashboard = new DM_Dashboard;
  	 $return = $dm_dashboard -> dashboard_links();

  	 return $return;

  }

  function dm_compare_colleges() {

  	 $dm_comparator = new DM_Comparator;
  	 $return = $dm_comparator -> compare_colleges_block();

  	 return $return;

  }


} // End class

$shortcodes = new DM_Shortcodes();
