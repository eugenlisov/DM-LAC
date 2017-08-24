<?php
/**
 * Use like this: $dm_tab = new DM_Tab;
 */
class DM_Tab {

  public function get_tab_content( $tab ) {

    $return = '';

    $tab_id             	= $tab -> id;
    $tab_name           	= $tab -> tab_name;
    $tab_title           	= $tab -> tab_title;
    $tab_intro 						= $tab -> tab_intro;
    $tab_type             = $tab -> type;
    $protected_narrative 	= $tab -> protected_narrative;
    $font_awesome_class 	= $tab -> font_awesome_class;

    $slug     =  strtolower( str_replace("& ", "", $tab_name) );
    $slug     =  str_replace(" ", "-", $slug);
    $active   = ( $tab_type == 'overview' ) ? 'in active' : '';

    $return .= '<div id="' . $slug . '" class="tab-pane fade ' . $active . '">
                <h3>' . $tab_title . '</h3>';

    $current_tab_content = '';

    $current_tab_content .= '';
    if ( $tab_type == 'overview' ) {

    } else {
      $return .= '<div class="tab-intro">' . $tab_intro . '</div>';
    }



    // echo 'Tab type is ' . $tab_type;

    switch ($tab_type) {
      case 'overview':
        $return .= $this -> get_overview_tab_content( $tab );
        break;
      default: // regular
        $return .= $this -> get_regular_tab_content( $tab );
        break;
    }

    $return .= '</div>';

    // NOTE: No longer need this. We only have a CTA btn at the bottom, so I removed the modal and the triggers.
    // $dm_modal = new DM_Modal;
    // $return .= $dm_modal -> call_to_action_modal();

    echo $return;

  }

  private function get_overview_tab_content( $tab ) {

    // echo '<pre>';
    // print_r( $tab );
    // echo '</pre>';
    $return = '<div class="dm-overview-tab-speciffic">';
    $return .= get_field( "school_overview" );
    $return .= '</div>';

    $return .= $this -> get_overview_tab_regular_or_protected_content( $tab );

    return $return;
  }

  private function get_regular_tab_content( $tab = '') {

      $tab_id = $tab -> id;

      $dm_question = new DM_Question;
      $tab_questions_ids = $this -> get_tab_questions( $tab_id );
      // echo '<pre>';
      // print_r( $tab_questions_ids );
      // echo '</pre>';

      $regular_content = '';

      foreach ($tab_questions_ids as $key => $question_id) {
          $regular_content .= $dm_question -> get_question_block( $question_id );
      }

      $return = $this -> get_regular_or_protected_content( $regular_content, $tab );

      return $return;
  }



/**
 * Only used to generate the global variables. Not used directly in the scripts.
 * @return [type] [description]
 */
  function get_all_questions_by_tabs() {

    global $wpdb;

    // Get the data from the 'dm_survey_responses' table
    $sql_tabs_questions_list = 'SELECT * FROM `dm_survey_tab_questions`
                                ORDER BY
                                tab_id ASC, question_id ASC';
    $tabs_questions_list_raw = $wpdb->get_results( $sql_tabs_questions_list );
    // echo '<pre>';
    // print_r ( $tabs_questions_list_raw );
    // echo '</pre>';
    $tabs_questions_list = array();

    foreach ($tabs_questions_list_raw as $key => $value) {
      $tabs_questions_list[$value -> tab_id][] = $value -> question_id;
    }

    // echo '<pre>';
    // print_r ( $tabs_questions_list );
    // echo '</pre>';

    return $tabs_questions_list;

    // echo '<pre>';
    // print_r($tabs_questions_list);
    // echo '</pre>';

  }

  /*
  *
  *
  */
  function get_tab_questions( $tab_id = '' ) {

    if ( !$tab_id ) return false;

    global $global_all_questions_by_tabs;

    // echo 'Tab ID: ' . $tab_id;
    //
    // echo '<pre>';
    // print_r( $global_all_questions_by_tabs );
    // echo '</pre>';

    return $global_all_questions_by_tabs[$tab_id];


    // // // Get the data from the 'dm_survey_responses' table
    // global $wpdb;
    // $sql_tabs_questions_list = 'SELECT * FROM `dm_survey_tab_questions`
    //                             WHERE tab_id = ' . $tab_id . '
    //                             ORDER BY
    //                             tab_id ASC, question_id ASC';
    // $tabs_questions_list_raw = $wpdb->get_results( $sql_tabs_questions_list );
    //
    // $tabs_questions_list = array();
    //
    // foreach ($tabs_questions_list_raw as $key => $value) {
    //   $tabs_questions_list[] = $value -> question_id;
    // }
    //
    // // return $tabs_questions_list;
    //
    // echo '<pre>';
    // print_r($tabs_questions_list);
    // echo '</pre>';

  }

  public function get_protected_narrative( $tab = '' ) {

    $protected_box = '<div class="row dm-protected-box">
                        <div class="col-md-2 dm-triangle">
                          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-10 dm-protected-narrative">
                        <p>' . $tab -> protected_narrative . 	'</p>
                        <p>To get access to this content, you need to <a href="' . get_permalink( PAGE_SIGNUP ) . '?rid=pv47iM">be a member</a>.</p>
                        </div>
                      </div>';

    $dm_call_to_action = new DM_CallToAction;
    $call_to_action = $dm_call_to_action -> default_tab_call_to_action();

    return $call_to_action;

  }

  public function get_regular_or_protected_content( $regular_content = '' , $tab = '' ) {

    $protected_box = $this -> get_protected_narrative( $tab );

    $allowed_shortcode  = "[MM_Access_Decision access='true']" . $regular_content . "[/MM_Access_Decision]";
    $denied_shortcode   = "[MM_Access_Decision access='false']" . $protected_box . "[/MM_Access_Decision]";

    $return = do_shortcode( $allowed_shortcode );
    $return .= do_shortcode( $denied_shortcode );

    return $return;

  }

  public function get_overview_tab_regular_or_protected_content( $tab = '' ) {

    // echo '<pre>';
    // print_r ( $tab );
    // echo '</pre>';


    $allowed_shortcode  = "[MM_Access_Decision access='true']" . $tab -> protected_narrative . "[/MM_Access_Decision]";
    $denied_shortcode   = "[MM_Access_Decision access='false']" . $tab -> tab_intro . "[/MM_Access_Decision]";

    $return = '<div class="dm-overview-tab-general">';
    $return .= do_shortcode( $allowed_shortcode );
    $return .= do_shortcode( $denied_shortcode );
    $return .= '</div>';

    return $return;

  }


  public function get_tabs_placeholder() {

    $return = '<div class="entry dm-tabs-no-datapoints-placeholder">
                <h3>Voice of the Student Research Findings</h3>
                <p><strong>Data collection for this college currently in progress.</strong></p>
                <p>For over 120 liberal arts colleges, students have provided us with the “inside scoop” on what is really happening on campus. <a href="' . get_permalink( PAGE_PREMIUM_VSL3 ) . '">Click here to learn more</a>.</p>
              </div>';

    return $return;
  }





} // End Class
  ?>
