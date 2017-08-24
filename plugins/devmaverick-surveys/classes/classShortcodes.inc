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
    add_shortcode('dm-contact-data-widget', array( $this, 'dm_contact_data_widget' ) );
    add_shortcode('dm-checkout', array( $this, 'dm_checkout' ) );
    add_shortcode('dm-vsl-cta-btn', array( $this, 'dm_vsl_cta_btn' ) );
    add_shortcode('dm-data-points-count', array( $this, 'dm_data_points_count' ) );


    add_shortcode('dm-test-pdf', array( $this, 'dm_test_pdf' ) );

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
  	 $return = $dm_comparator -> compare_colleges_select_block();

    //  $return .= $dm_comparator -> college_comparison_block();

  	 return $return;

  }

  function dm_contact_data_widget() {

    $dm_school = new DM_School;
    $return = $dm_school -> school_contact_data_widget();

    return $return;

  }

  function dm_checkout() {

    $dm_checkout = new DM_Checkout;
    $return = $dm_checkout -> checkout_page_content();

    return $return;

  }

  function dm_vsl_cta_btn() {

    $dm_vsl = new DM_VSL;
    $return = $dm_vsl -> cta_btn();

    return $return;

  }

  function dm_data_points_count() {

    $dm_school = new DM_School;
    $return =  $dm_school -> get_data_points_count( get_the_ID() );

    return $return;

  }






  function dm_test_pdf() {

    error_reporting(0);

    $dm_comparator    = new DM_Comparator;
    $dm_comparatorPDF = new DM_ComparatorPDF;


    $return = $dm_comparatorPDF -> get_pdf_form( 209, 479 );

    $return .= '<strong>Original:</strong> <br />';
    $return .= $dm_comparator -> college_comparison_block( 209, 479 );

    $return .= '<strong>PDF:</strong> <br />';
    $return .= $dm_comparatorPDF -> college_comparison_block( 209, 479 );
    $return .= $dm_comparatorPDF -> style();

    return $return;

  }

} // End class

$shortcodes = new DM_Shortcodes();
