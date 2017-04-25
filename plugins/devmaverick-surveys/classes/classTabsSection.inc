<?php
/**
 * Use like this: $dm_tabs_section = new DM_TabsSection;
 */
class DM_TabsSection {


  // public function display_tabs_section() {
  //   $this -> tabs_section ();
  // }



  public function display_tabs_section() {
  	// wp_enqueue_style('dm-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
    wp_enqueue_style('dm-font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  	wp_enqueue_style('dm-tabs', plugins_url() . '/devmaverick-surveys/assets/css/tabs.css');

    wp_enqueue_script('dm-bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), '1.0.0', true );
  	wp_enqueue_script('dm-d3-script', 'http://d3js.org/d3.v3.min.js', array(), '1.0.0', true );

  	wp_enqueue_script('dm-tabs', plugins_url() . '/devmaverick-surveys/assets/js/tabs.js', array(), '1.0.0', true );



  	// echo 'TEsting smart tags';

  // 	echo do_shortcode("
  // [MM_Access_Decision access='true']
  //
  // TEST EUGEN You've had access to this page since [MM_Content_Data name='dateAvailable']. Click here to visit the page: <a href=\"[MM_Content_Link]\">[MM_Content_Data name='title']</a>
  //
  // [/MM_Access_Decision]
  //
  //
  // [MM_Access_Decision access='false']
  //
  // TEST EUGEN You don't have access to [MM_Content_Data name='title']. Click here to purchase access: <a href=\"[MM_Purchase_Link productId='1']\">Get Access</a>
  //
  // [/MM_Access_Decision]");

  $dm_school = new DM_School;

  	$iped                = $dm_school -> get_iped(); // S-ar putea ca aici sa nu fie neaparat necesar.

  	// If there is not IPED associated with this school, just not display the Tabs.
  	if ($iped == '') return;



    $tabs_list           = $this -> get_tabs_list();
    $all_questions_list  = get_all_questions_list();
    $tabs_questions      = get_tabs_questions();
  	$response_averages   = get_response_averages(); // TODO. Trebuie refacut
    $response_averages_by_iped   = get_response_averages_by_iped( $iped ); // OK - functie refacuta
  	$lac_averages 			 = get_response_averages(); // To be used on the comparisons tab;

    $responses_max       = get_responses_max_score(); // TODO de verificat la ce foloseste  asta
    $responses_min       = get_responses_min_score(); // TODO de verificat la ce foloseste  asta


  	$charts_data 						= dm_get_charts_data( $iped );
  	$comparisons_chart_data = dm_get_comparisons_charts_data( $iped );


  	// Nu stiu daca e ce TRebuie
  	$responses_count_by_question_and_iped = get_response_count_by_question_and_iped( $iped ); //- Trebuie salvat aici numarul de raspunsuri pentru fiecare intrebare.
  	$responses_by_iped										= get_response_count_by_iped( $iped );

  	$question_options_list 								= get_question_options_list();

    // echo '<pre class="col-md-6">';
    // print_r($tabs_questions);
    // echo '</pre>';
  	// echo '<pre class="col-md-6">';
  	// print_r($lac_averages);
  	// echo '</pre>';

  	global $post;
  	$post_id =  $post->ID;


  	$selected_open_ended_questions = get_post_meta($post_id, 'dm_open_ended_questions', true);


  	?>

    <!-- This is where the code goes -->




  		<div class="dm-school-tabs bs-example" school-iped="<?php echo $iped; ?>">
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

    $return = '';

    foreach ($tabs_list as $key => $tab) {

      $tab_id             = $tab -> id;
      $tab_name           = $tab -> tab_name;
      $font_awesome_class = $tab -> font_awesome_class;

      $slug     =  strtolower( str_replace(" ", "-", $tab_name) );
      $active   = ( $key == 0 ) ? 'class="active"' : '';

      $return .= '<li ' . $active. '><a data-toggle="tab" href="#' . $slug . '"><i class="fa ' . $font_awesome_class . '" aria-hidden="true"></i>  ' . $tab_name . '</a></li>';
      // echo $slug . '<br />';
      # code...
    }

    return $return;
  }




} // End Class



function dm_call_tabs_section() {

  // Declare the global variables to be used with all the classes and functions

  global $global_question_options_list;
  global $global_responses_count_by_question_and_iped;
  global $global_responses_by_iped;
  global $global_response_averages_by_iped; // Cred ca used doar la NPS
  global $global_all_questions;
  global $global_all_questions_by_tabs;
  global $global_open_ended_question_list;
  global $q_id_match;

  $dm_school    = new DM_School;
  $dm_question  = new DM_Question;
  $dm_tab       = new DM_Tab;

  $iped = $dm_school -> get_iped(); // S-ar putea ca aici sa nu fie neaparat necesar.

  // echo 'IPED IS: ' . $iped;

  $global_question_options_list 								= get_question_options_list();
  $global_responses_count_by_question_and_iped  = get_response_count_by_question_and_iped( $iped );
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
