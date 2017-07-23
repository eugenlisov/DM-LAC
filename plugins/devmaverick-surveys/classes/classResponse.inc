<?php
/**
 * Use like this: $dm_response = new DM_Response;
 */
class DM_Response {

  public function get_total_response_count_by_question_and_iped( $iped = '' ) {
    if ( ! $iped ) {

      $dm_school = new DM_School;
      $iped = $dm_school -> get_iped();
    }

    global $wpdb;
    $sql = 'SELECT q_all_number, COUNT(DISTINCT(respondent_id)) AS count FROM `dm_survey_responses` WHERE iped = ' . $iped . ' GROUP BY q_all_number';

    $result = $wpdb->get_results( $sql );

    foreach ($result as $key => $value) {
      $return[$value -> q_all_number] = $value -> count;
    }

    if( $return ) {
      return $return;
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
