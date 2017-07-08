<?php
class DM_Ajax {


  /**
   * Constructor
   */
  public function __construct() {

      add_action( 'wp_ajax_nopriv_dm_load_college_comparison_table', array( $this, 'load_college_comparison_table' ) );
      add_action( 'wp_ajax_dm_load_college_comparison_table', array( $this, 'load_college_comparison_table' ) );



      add_action( 'wp_ajax_nopriv_load_question_responses', array( $this, 'load_question_responses' ) );
      add_action( 'wp_ajax_load_question_responses', array( $this, 'load_question_responses' ) );

      add_action( 'wp_ajax_nopriv_save_comparator_setup', array( $this, 'save_comparator_setup' ) );
      add_action( 'wp_ajax_save_comparator_setup', array( $this, 'save_comparator_setup' ) );

      add_action( 'wp_ajax_nopriv_load_college_comparison_block', array( $this, 'load_college_comparison_block' ) );
      add_action( 'wp_ajax_load_college_comparison_block', array( $this, 'load_college_comparison_block' ) );






  }


  public function load_college_comparison_table() {

  }

  public function load_question_responses() {

    $question = $_POST['data']['targeted_question'];
    $row      = $_POST['data']['row'];

    $dm_comparator = new DM_Comparator;
    $result = $dm_comparator -> get_responses_dropdown_for_question( $question, $row );

    die(json_encode( $result ));
  }




  public function save_comparator_setup() {

    $dm_comparator = new DM_Comparator;
    $result = $dm_comparator -> ajax_save_comparator_setup();


    // $params = array();
    // parse_str( $_POST['data'], $params);
    //
    // //
    // //
    // // $form_data = $_POST['data'];
    // print_r( $params );
    // $dm_comparator = new DM_Comparator;
    //
    // $result = $dm_comparator -> get_responses_dropdown_for_question( $question );
    //
    // die(json_encode( $result ));
  }

  public function load_college_comparison_block() {

    $college_1 = $_POST['data']['college_1'];
    $college_2 = $_POST['data']['college_2'];


    $dm_comparator = new DM_Comparator;
    $result = $dm_comparator -> college_comparison_block( $college_1, $college_2 );

    die(json_encode( $result ));
    
  }


} // End class

$ajax = new DM_Ajax();
