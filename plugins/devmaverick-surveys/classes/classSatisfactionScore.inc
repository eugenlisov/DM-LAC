<?php
/**
 * Use like this: $dm_satisfaction_score = new DM_SatisfactionScore;
 */
class DM_SatisfactionScore {

  public function get_satisfaction_score_by_iped( $iped = '' ) {
    if ( ! $iped ) {

      $dm_school = new DM_School;
      $iped = $dm_school -> get_iped();
    }

    global $wpdb;
    $sql = 'SELECT  ROUND( AVG(CASE WHEN q_score <> 0 THEN q_score ELSE NULL END), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' AND q_all_number = 6';

    $result = $wpdb->get_results( $sql );

    if( $result ) {
      $satisfaction_score = $result[0] -> average - 10;
      return $satisfaction_score;
    } else {
      return 0;
    }

  }








  function get_response_averages_by_iped( $iped = '' ) {
    if ($iped == '') return 0;

    global $wpdb;

    // New way, grab data from the averages table

    $sql_response_average_by_iped = 'SELECT q_all_number, school_ave FROM dm_school_averages WHERE iped = ' . $iped;


    // Old way

    // Get the data from the 'dm_survey_responses' table
    // $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(q_score), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
    // $sql_response_averages = 'SELECT q_all_number, ROUND( AVG(CASE WHEN q_score <> 0 THEN q_score ELSE NULL END), 2) as average FROM `dm_survey_responses` WHERE `iped` = ' . $iped . ' GROUP BY (`q_all_number`) ORDER BY `ID` ASC';
    $response_averages = $wpdb->get_results( $sql_response_average_by_iped );
    // $response_averages = $response_averages -> count;

    $response_averages_by_id = array();
    foreach ($response_averages as $key => $average) {
      $response_averages_by_id[$average -> q_all_number] = $average;
    }

    // echo '<pre>';
    // print_r($response_averages_by_id);
    // echo '</pre>';

    return $response_averages_by_id;





  }


}
