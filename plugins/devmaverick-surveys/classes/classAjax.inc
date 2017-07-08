<?php
class DM_Ajax {


  /**
   * Constructor
   */
  public function __construct() {

      add_action( 'wp_ajax_nopriv_dm_load_college_comparison_table', array( $this, 'load_college_comparison_table' ) );
      add_action( 'wp_ajax_dm_load_college_comparison_table', array( $this, 'load_college_comparison_table' ) );

  }


  public function load_college_comparison_table() {

  }


} // End class

$ajax = new DM_Ajax();
