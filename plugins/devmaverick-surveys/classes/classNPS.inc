<?php
/**
 * Use like this: $dm_nps = new DM_NPS;
 */

class DM_NPS {

  public function get_nps( $iped = '' ) {
    if ( !$iped ) return false;

    global $wpdb;

    $sql = 'SELECT nps FROM dm_nps WHERE iped = ' . $iped;

    $result = $wpdb->get_row( $sql );

    return $result -> nps;

  }


  /**
   * The main processing method. Call all the functions to calculate and save the NPS.
   * @return [type] [description]
   */
  public function process_all_nps() {

    /*
    Calculate the NPS for all the IPEDS in the latest import
     */
    $nps_by_iped_array = $this -> calculate_all_nps();


    /*
    Remova ALL previous values from the dmnps table
     */
    $result = $this -> empty_nps_table();


    /*
    Save all the calculated NPS to the database
     */
    $result = $this -> save_all_nps( $nps_by_iped_array );

    // if (!$result) {
    //   echo 'ERROR! somewhere<br />';
    // } else {
    //   echo 'Successfully added the NPS scores<br />';
    // }

  }



  /**
   * [calculate_all_nps description]
   * @return array - of ipeds and freshly calculated NPS
   */
  private function calculate_all_nps() {

    $counts_by_iped_and_response = $this -> query_response_counts_by_iped_and_response();

    foreach ($counts_by_iped_and_response as $iped => $counts_by_response) {
      $nps_by_iped_array[$iped] = $this -> calculate_nps( $counts_by_response );
    }

    return $nps_by_iped_array;

  }

  private function save_all_nps( $nps_by_iped_array = '' ) {

    global $wpdb;

    $count = count( $nps_by_iped_array );
    $index = 1;
    foreach ($nps_by_iped_array as $iped => $nps) {
      $values_string .= ' ( ' . $iped . ', ' . $nps . ')';
      $values_string .= ($index != $count) ? ',' : '';

      $index++;
    }

    $sql = 'INSERT INTO dm_nps ( iped, nps ) VALUES ' . $values_string;

    $result = $wpdb->query( $sql );

    return $result;

    // if (!$result) {
    //   echo 'ERROR! somewhere<br />';
    // } else {
    //   echo 'Successfully added the NPS scores<br />';
    // }

  }

  private function empty_nps_table() {

    global $wpdb;

    $sql = 'TRUNCATE TABLE dm_nps';

    $result = $wpdb->query( $sql );

    if (!$result) {
      // echo 'ERROR! somewhere<br />';
    } else {
      // echo 'Successfully added the NPS scores<br />';
    }
  }




  /**
   * [calculate_nps description]
   * @param  string $counts_by_response [description]
   * @return [type]                     [description]
   */
  private function calculate_nps( $counts_by_response = '') {

    // $counts_by_iped_and_response = $this -> query_response_counts_by_iped_and_response();

    // $counts_by_response = $this -> query_response_counts_by_response();

    $response_count = array_sum( $counts_by_response );

    $promoters_count = 0;
    $detractors_count = 0;

    for ($i=0; $i <=6 ; $i++) {
      $detractors_count += $counts_by_response[$i];
    }
    for ($i=9; $i <=10 ; $i++) {
      $promoters_count += $counts_by_response[$i];
    }

    $detractors_percent = $detractors_count / $response_count * 100;
    $promoters_percent = $promoters_count / $response_count * 100;
    $nps = $promoters_percent - $detractors_percent;

    return round( $nps );

    // echo '$response_count: ' . $response_count . '<br />';
    // echo '$detractors_count: ' . $detractors_count . '<br />';
    // echo '$promoters_count: ' . $promoters_count . '<br />';
    // echo '$detractors_percent: ' . $detractors_percent . '<br />';
    // echo '$promoters_percent: ' . $promoters_percent . '<br />';
    // echo '$nps: ' . $nps . '<br />';

    // echo '<pre>';
    // print_r( $return_array );
    // echo array_sum( $return_array );
    // echo '</pre>';

    // trebuie sa foloseasca   $global_responses_count_by_question_and_iped  = get_response_count_by_question_and_iped( $iped );
    //
    //
    //
    //
    // So,
    // Count (9's+10's ) / count (1’s thought 10’s) * 100
    // MINUS
    // Count ( 0 through 6's ) / count (1’s thought 10’s) * 100
    //
    // For example, if 9’s and 10’ make up 20% of responses AND 1’s thought 6’s are 40% THEN
    // NPS = 20% - 40% = -20.
    //
    // Facem un tabel separat pentru asta.

  }

  /**
   * TODO Deprecated
   * [query_response_counts_by_response description]
   * @return array [description]
   */
  // public function query_response_counts_by_response() {
  //
  //    global $wpdb;
  //
  //     $sql = 'SELECT
  //               response_other AS response,
  //               COUNT(*) AS `count`
  //             FROM
  //               dm_survey_responses
  //             WHERE
  //              `q_all_number` = 3
  //             GROUP BY
  //               response_other';
  //
  //     global $wpdb;
  //
  //     $myrows = $wpdb->get_results( $sql );
  //
  //     foreach ($myrows as $key => $value) {
  //       $response = $value -> response;
  //       $count = $value -> count;
  //       $counts_by_response[$response] = $count;
  //     }
  //
  //     return $counts_by_response;
  //
  // }

  /**
   * [query_response_counts_by_iped_and_response description]
   * @return array [description]
   */
  private function query_response_counts_by_iped_and_response() {

     global $wpdb;

      $sql = 'SELECT
                iped,
                response_other AS response,
                COUNT(*) AS `count`
              FROM
                dm_survey_responses
              WHERE
                `q_all_number` = 3
              GROUP BY
                iped,
                response_other';

      global $wpdb;

      $myrows = $wpdb->get_results( $sql );

      foreach ($myrows as $key => $value) {
        $iped = $value -> iped;
        $response = $value -> response;
        $count = $value -> count;
        $counts_by_iped_and_response[$iped][$response] = $count;
      }

      // echo '<pre>';
      // print_r( $counts_by_iped_and_response );
      // echo '</pre>';

      return $counts_by_iped_and_response;

  }
  public function nps_traffic_lights( $nps = '', $college_id ) {

    if ( $nps ) {

      if ( $nps >= 41 ) {
        $light = 'green';
        $message = 'Based on feedback from current students, ' . get_the_title( $college_id ) . ' is highly recommended.';
      } elseif ( $nps >= 30 && $nps < 40 ) {
        $light = 'yellow';
        $message = 'Current students of ' . get_the_title( $college_id ) . ' don\'t have a strong opinion pro or against it.';
      } else {
        $light = 'red';
        $message = 'Current students aren\'t eager to recommend ' . get_the_title( $college_id ) . '.';
      }

    $return = '<div class="row dm-nps-traffic-light-container">
                  <div class="col-md-2">
                      <div class="dm-nps-traffic-lights ' . $light . '"></div>
                  </div>
                  <div class="col-md-10 dm-nps-traffic-lights-message">
                  ' . $message  . '
                  </div>
                </div>';
    }
    return $return;

  }



} // End Class
 ?>
