<?php
/**
 * Use like this: $dm_tabs_section = new DM_TabsSection;
 */
class DM_TabsSection {


  // public function display_tabs_section() {
  //   $this -> tabs_section ();
  // }



  public function display_tabs_section() {
  	wp_enqueue_style( 'dm-bootstrap' );
    wp_enqueue_style( 'dm-font-awesome' );
    wp_enqueue_script( 'dm-bootstrap' );
    wp_enqueue_script( 'dm-tabs' );

  $dm_school = new DM_School;

  	$iped                = $dm_school -> get_iped(); // S-ar putea ca aici sa nu fie neaparat necesar.

  	// If there is not IPED associated with this school, just not display the Tabs.
  	if ($iped == '') return;


    $tabs_list = $this -> get_tabs_list();
    // echo '<pre>';
    // print_r ( $tabs_list );
    // echo '</pre>';




  	?>

    <!-- This is where the code goes -->



  		<div class="dm-school-tabs" school-iped="<?php echo $iped; ?>">
  		    <ul class="nav nav-tabs">
              <?php
                echo $this -> get_tabs_navigation( $tabs_list );
               ?>
  		    </ul>
  		    <div class="tab-content">

            <?php
            $dm_tab = new DM_Tab;

            foreach ($tabs_list as $key => $tab) {
              $dm_tab -> get_tab_content( $tab );
            }
             ?>
  		    </div>
  		</div>

  		<!-- This is where the TAB code goes -->

  	<?php
  }


  public function get_tabs_list() {

    global $wpdb;

    // Get the data from the 'dm_survey_responses' table
    // $sql_tabs_list = 'SELECT * FROM `dm_survey_tabs` ORDER BY type DESC';
    $sql_tabs_list = 'SELECT * FROM `dm_survey_tabs`';
    $tabs_list = $wpdb->get_results( $sql_tabs_list );

    return $tabs_list;

  }

  /**
   * Grab the tab navigation
   * @param  string $tabs_list [description]
   * @return string            The content of the tab navigation
   */
  public function get_tabs_navigation( $tabs_list = '' ) {

    if ( !$tabs_list ) return false;

    // echo '<pre>';
    // print_r( $tabs_list );
    // echo '</pre>';

    $return = '';

    foreach ($tabs_list as $key => $tab) {

      $tab_id             = $tab -> id;
      $tab_name           = $tab -> tab_name;
      $font_awesome_class = $tab -> font_awesome_class;

      $slug     =  strtolower( str_replace("& ", "", $tab_name) );
      $slug     =  str_replace(" ", "-", $slug);
      $active   = ( $key == 0 ) ? 'class="active"' : '';

      $return .= '<li ' . $active. '><a data-toggle="tab" href="#' . $slug . '"><i class="fa ' . $font_awesome_class . '" aria-hidden="true"></i>  ' . $tab_name . '</a></li>';
      // echo $slug . '<br />';
      # code...
    }

    return $return;
  }




} // End Class



function dm_call_tabs_section() {

  if ( ! is_singular( 'schools' ) ) {
    return;
  }

  $membership_id = \GFLead\Services\Membership::membership_level_id();
  // echo 'Current memebrship ID: ' . $membership_id;


  if ( $membership_id != 2 && ! current_user_can( 'administrator' ) ) {
      return;
  }

  $dm_school = new DM_School;
  $dm_tab       = new DM_Tab;

  $data_points_count = $dm_school -> get_data_points_count( get_the_ID() );


  //If there are no data points, display tabs placeholder
  if ( $data_points_count == 0 ) {

    echo $dm_tab -> get_tabs_placeholder();

    return;
  }


  // Declare the global variables to be used with all the classes and functions

  global $global_question_options_list;
  global $global_responses_count_by_question_and_iped;
  global $global_total_responses_count_by_question_and_iped;
  global $global_responses_by_iped;
  global $global_response_averages_by_iped; // Cred ca used doar la NPS
  global $global_all_questions;
  global $global_all_questions_by_tabs;
  global $global_open_ended_question_list;
  global $q_id_match;

  $dm_question  = new DM_Question;
  $dm_response  = new DM_Response;

  $iped = $dm_school -> get_iped(); // S-ar putea ca aici sa nu fie neaparat necesar.

  // echo 'IPED IS: ' . $iped;

  $global_question_options_list 								= get_question_options_list();
  $global_responses_count_by_question_and_iped  = get_response_count_by_question_and_iped( $iped );
  $global_total_responses_count_by_question_and_iped  = $dm_response -> get_total_response_count_by_question_and_iped( $iped );
  $global_responses_by_iped					            = get_response_count_by_iped( $iped );
  $global_response_averages_by_iped             = get_response_averages_by_iped( $iped ); // Cred ca used doar la NPS
  $global_all_questions                         = $dm_question -> get_all_questions_list();
  $global_all_questions_by_tabs                 = $dm_tab -> get_all_questions_by_tabs();
  $global_open_ended_question_list              = $dm_question -> get_open_ended_questions_list();
  $q_id_match                                   = $dm_question -> match_q_id_to_q_all_id();

  $dm_tabs_section = new DM_TabsSection;
  $dm_tabs_section -> display_tabs_section();
}
  add_action( 'genesis_after_loop', 'dm_call_tabs_section', 10, 10 );

?>
