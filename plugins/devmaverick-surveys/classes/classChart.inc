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

  public function get_comparison_chart( $question_id = '') {

    $dm_question = new DM_Question;
    $dm_current_question = $dm_question -> get_question( $question_id );


    $percent_lac            = $dm_current_question -> percent_lac;
    $percent_iped           = $dm_current_question -> percent_iped;

    if (!$question_id) return false;

    $chart_string = '';

    $chart_string .= '
        <div class="row dm-option" option-percent="' . $percent_lac . '">
          <div class="col-md-3 dm-option-label">
            <strong>' . get_the_title() . ':</strong> ' . $response_iped . '
          </div>
          <div class="col-md-9">
            <div class="progress">
              <div class="progress-bar progress-bar-comp-iped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent_iped . '%">
                <span class="dm-progress-bar-content">' . $percent_iped . '%</span>
              </div>
            </div>
          </div>
        </div>';
    $chart_string .= '
        <div class="row dm-option" option-percent="' . $percent_iped . '">
          <div class="col-md-3 dm-option-label">
            <strong>All colleges:</strong>
            ' . $response_lac . '
          </div>
          <div class="col-md-9">
            <div class="progress">
              <div class="progress-bar progress-bar-comp-lac" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent_lac . '%">
                <span class="dm-progress-bar-content">' . $percent_lac . '%</span>
              </div>
            </div>
          </div>
        </div>';

    return $chart_string;

  }


  private function get_q3_chart( $question_id = '' ) {

      $dm_school = new DM_School;
      $nps = $dm_school -> get_nps( $iped );

      $comparison_class = ( $nps > 0 ) ? 'net-promoter' : 'net-detractor';

      $return .= '<div class="dm-nps-container" title="' . $hover_caption . '">
                    Net Promoter Score (NPC) = <span class="' . $comparison_class . '">'  . $nps . '</span>
                  </div>';

      return $return;
  }

  private function get_q6_chart( $question_id = '' ) {

      $average_lac 	= $response_averages[$question_id] -> average ;
      $average_iped = $response_averages_by_iped[$question_id] -> school_ave ;

      $average_lac_round 	= round( $response_averages[$question_id] -> average );
      $average_iped_round = round( $response_averages_by_iped[$question_id] -> school_ave );

      $response_lac 	= $question_options_list[$question_id][$average_lac_round] -> option_text;
      $response_iped 	= $question_options_list[$question_id][$average_iped_round] -> option_text;

      // $current_question_return .= 'Current question ' . $question_id . ' <br />';
      // $current_question_return .= '$average_lac_round ' . $average_lac . ' <br />';
      // $current_question_return .= '$average_iped_round ' . $average_iped . ' <br />';
      // $current_question_return .= '$response_iped ' . $response_iped . ' <br />';
      // $current_question_return .= '$average_iped_round ' . $average_iped_round . ' <br />';


      $current_question_option = $charts_data[$question_id];
      // echo '<pre>';
      // print_r($current_question_option);
      // echo '</pre>';
      $current_option = $current_question_option[$average_iped_round];
      // echo '<pre>';
      // print_r( $current_option );
      // echo '</pre>';

      $option_text = $current_option -> option_text;
      $option_percent_q6 = $average_iped / 10 * 100;

      $current_question_return .= '<p class="dm-q6-chart-caption">On a 0 to 10 scale, this is what students rated ' . get_the_title() . '.</p>';
      $current_question_return .= '
          <div class="row dm-option" option-percent="' . $option_percent . '">
            <div class="col-md-3 dm-option-label">

              Satisfaction Level:
            </div>
            <div class="col-md-9">
              <div class="progress">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $option_percent_q6 . '%">
                  <span class="dm-progress-bar-content">' . $option_percent_q6 . '%</span>
                </div>
              </div>
            </div>
          </div>';


  }

  private function get_regular_q_chart( $question_id = '' ) {

              $current_question_chart_return = '<div class="dm-chart-container">';

              $dm_school = new DM_School;
            	$iped = $dm_school -> get_iped();

            	$chart_data = $this -> dm_get_charts_data( $question_id, $iped );

              // echo '<pre>';
              // print_r($chart_data);
              // echo '</pre>';


              if ($chart_data){
                foreach ($chart_data as $key => $current_option) {
                  $option_text = $current_option -> option_text;
                  $option_percent = $current_option -> percent;

                  $current_question_chart_return .= '
                      <div class="row dm-option" option-percent="' . $option_percent . '">
                        <div class="col-md-3 dm-option-label">
                          ' . $option_text . '
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
    global $global_responses_by_iped;

  	//Once we have the two above, we merge them into a larger array;
  	$question_options_count_array = array();  	$max_percent_key = 0;

      $question = $global_question_options_list[$question_id] ;
    if ($question) {
  		foreach ($question as $key_option => $option) {


  			if ($global_responses_count_by_question_and_iped[$question_id][$key_option]) {
  				$count = $global_responses_count_by_question_and_iped[$question_id][$key_option];
  				$percent = round( $count / $global_responses_by_iped * 100 );

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
