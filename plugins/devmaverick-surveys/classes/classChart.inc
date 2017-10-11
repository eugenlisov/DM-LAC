<?php
/**
 * Use like this: $dm_chart = new DM_Chart;
 */
class DM_Chart {

  public function get_chart( $question_id = '') {

    if (!$question_id) return false;

    $chart_string = '';

    switch ($question_id) {
      case 3:
        $chart_string = $this -> get_q3_chart( $question_id );
        break;
      case 6:
        $chart_string = $this -> get_q6_chart( $question_id );
        break;
      default:
        $chart_string = $this -> get_regular_q_chart( $question_id );
        break;
    }

    return $chart_string;

  }

  private function get_q3_chart( $question_id = '' ) {

      $dm_nps = new DM_NPS;
      $dm_school = new DM_School;
      $college_id = get_the_ID();
      $nps = $dm_school -> get_school_nps ( $college_id );

      $return = $dm_nps -> nps_traffic_lights( $nps, $college_id );

      return $return;
  }

  private function get_q6_chart( $question_id = '' ) {
      // NOTE The number of the q5 is 6 in this case


      $dm_satisfaction_score = new DM_SatisfactionScore;
      $satisfaction_score = $dm_satisfaction_score -> get_satisfaction_score_by_iped();

          return '<div class="dm-satisfaction-score-chart-container">
                    <div class="dm-chart-counter-container">
                      <div class="dm-chart-counter" id="dm-chart-counter"></div>
                    </div>

                    <div class="chart">
                      <div class="ct-chart ct-golden-section"></div>
                    </div>
                  </div>' . $this -> echo_satisfaction_score_var( $satisfaction_score );

  }

  /**
   * Functia asta este un gunoi curat.
   */
  private function echo_satisfaction_score_var( $satisfaction ) {

    $return = '<script type="text/javascript">
        var satisfactionScore = ' . $satisfaction . '
      </script>';

      return $return;

  }

  private function get_regular_q_chart( $question_id = '' ) {

              $current_question_chart_return = '<div class="dm-chart-container">';

              $dm_school = new DM_School;
            	$iped = $dm_school -> get_iped();

            	$chart_data = $this -> dm_get_charts_data( $question_id, $iped );

              // if ( $question_id == 26 ) {
              //
              //   echo '<pre>';
              //   print_r($chart_data);
              //   echo '</pre>';
              //
              // }



              if ($chart_data){
                foreach ($chart_data as $key => $current_option) {
                  $option_text = stripslashes ( $current_option -> option_text );
                  $pretty_option_text = stripslashes ( $current_option -> pretty_option_text );
                  $option_percent = $current_option -> percent;

                  $option = ( $pretty_option_text != '' ) ? $pretty_option_text : $option_text;

                  $current_question_chart_return .= '
                      <div class="row dm-option" option-percent="' . $option_percent . '">
                        <div class="col-md-3 dm-option-label">
                          ' . $option . '
                        </div>
                        <div class="col-md-9">
                          <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $option_percent . '%">
                              <span class="dm-progress-bar-content">' . $option_percent . '%</span>
                            </div>
                          </div>
                        </div>
                      </div>';


                }
              }

              $current_question_chart_return .= '</div>';


              return $current_question_chart_return;

  }





  private function dm_get_charts_data( $question_id = '', $iped = '' ) {

    global $global_question_options_list;
    global $global_responses_count_by_question_and_iped;
    global $global_total_responses_count_by_question_and_iped;
    global $global_responses_by_iped;

    // echo '<pre>';
    // print_r( $global_question_options_list );
    // echo '</pre>';
    $total_count = $global_total_responses_count_by_question_and_iped[$question_id];

  	//Once we have the two above, we merge them into a larger array;
  	$question_options_count_array = array();
    $max_percent_key = 0;

      $question = $global_question_options_list[$question_id] ;
    if ($question) {
  		foreach ($question as $key_option => $option) {


  			if ($global_responses_count_by_question_and_iped[$question_id][$key_option]) {
  				$count = $global_responses_count_by_question_and_iped[$question_id][$key_option];
  				$percent = round( $count / $total_count * 100, 1 );

  				$global_question_options_list[$question_id][$key_option] -> count = $count;
  				$global_question_options_list[$question_id][$key_option] -> percent = $percent;

  				$max_percent_key = ($percent > $max_percent) ? $key_option : $max_percent_key;
  			} else {
  				$global_question_options_list[$question_id][$key_option] -> count = 0;
  				$global_question_options_list[$question_id][$key_option] -> percent = 0;
  			}

  		}
  	}


  	return $global_question_options_list[$question_id];


  }



}
?>
