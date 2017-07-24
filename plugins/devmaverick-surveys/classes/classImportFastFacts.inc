<?php
/**
 * Use like this: $dm_import_fast_facts = new DM_ImportFastFacts;
 */
class DM_ImportFastFacts {

  public function import_surveys_form() {

    $return = '<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                 <input type="file" name="file">
                 <input type="submit" name="btn_submit" value="Upload File" />
               </form>';

    return $return;
  }



  public function process_import_file() {

    if ( ! $_FILES ) {
      return 'No file uploaded';
    }

    global $wpdb;

    $start = microtime(true);

    $school_ids_by_ipeds = $this -> get_school_ids_by_ipeds( $wpdb );

    // echo '<pre>';
    // print_r($school_ids_by_ipeds);
    // echo '</pre>';

    // Read the CSV file and save to a two dimensional array.
    $lines = $this -> get_array_from_csv();

       foreach ($lines as $key => $line) {
         if ($key == 0) continue;

        //  if ($key == 10) break;

         $this -> process_csv_line( $line, $school_ids_by_ipeds );
       }

       $time_elapsed_secs = microtime(true) - $start;
       echo '<br />Time elapsed: ' . $time_elapsed_secs;


  }


  public function get_array_from_csv() {
    // Read the CSV file and save to a two dimensional array.

    $fh = fopen($_FILES['file']['tmp_name'], 'r+');
    $lines = array();
    while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
      $lines[] = $row;
    }

    return $lines;
  }

  public function get_school_ids_by_ipeds( $wpdb ){

    $unique_schools_array         = array();

    $sql = "SELECT meta_value, post_id FROM `wp_postmeta` WHERE meta_key = 'school_iped' AND meta_value <> ''";

    $result = $wpdb->get_results( $sql );
      foreach ($result as $key => $value) {
        $return[$value -> meta_value] = $value -> post_id;
      }

    return $return;

  }

  public function process_csv_line( $line = '', $school_ids_by_ipeds = '' ) {

    $iped              = $line[1];
    $school_name       = $line[2];
    $average_net_price = $line[3];
    $retention_rate    = $line[4];
    $top_10_of_class   = $line[5];
    $percent_admitted  = $line[6];
    $sat_75            = $line[7];
    $sat_25            = $line[8];
    $act_75            = $line[9];
    $act_25            = $line[10];
    $graduation_rate   = $line[11];

    if ( $school_ids_by_ipeds[$iped] != '' ) {
      // echo 'EXISTS: ' . $school_name . '<br />';
    } else {
      return;
      // echo 'DOES NOT EXIST: ' . $school_name . '<br />';
      // $this -> get_school_by_title( $school_name, $iped ); // No longer necccesary after adding all the available  IPEDS
    }

    // Now that we're here, we have a $college_id

    $college_id = $school_ids_by_ipeds[$iped];

    echo '<strong><a href="' . get_permalink( $college_id ) . '" target="_blank">' . $school_name . '</a></strong><br />';
    // echo 'Average Net Price: ' . $average_net_price . '<br />';
    // echo 'IPED: ' . $iped . '<br />';
    // echo 'College ID: ' . $college_id . '<br />';

    $this -> save_average_net_price( $college_id, $average_net_price );
    $this -> save_freshmen_retention_rate( $college_id, $retention_rate );
    $this -> save_top_10_of_class( $college_id, $top_10_of_class );
    $this -> save_acceptance_rate( $college_id, $percent_admitted );
    $this -> save_graduation_rate( $college_id, $graduation_rate );
    $this -> save_test_data( $college_id, $sat_75, $sat_25, $act_75, $act_25 );


  }

  public function get_School_by_title( $school_name = '', $iped = '' ) {

    if ( ! $school_name ) return false;

    $post = get_page_by_title( $school_name, $output, 'schools' );

    if ( $post ) {

      // echo '<pre>';
      // print_r( $post );
      // echo '</pre>';

      echo 'POST WITHOUT IPED:' . $post -> post_title . '<br />';
      update_post_meta( $post -> ID, 'school_iped', $iped );
      echo 'Added IPED #' . $iped . ' to school ' . $post -> ID . '<br /><br />';

    } else {
      echo ' -> No such post. <br /><br />';
    }

  }

  public function save_average_net_price( $college_id = '', $average_net_price = '' ) {
    if ( ! $college_id ) return false;
    if ( ! $average_net_price ) return false;

    $average_net_price = str_replace( '$', '', $average_net_price );
    $average_net_price = str_replace( ',', '', $average_net_price );

    // Round numbers
    $rounded_net_price = round ( $average_net_price / 1000 ) * 1000;

      // echo 'Average net price: ' . intval( $rounded_net_price ) . ' <br />';

    $return = wp_set_post_terms( $college_id, $rounded_net_price, 'total_fees', false );

    if ( $return ) {
      echo 'Successfuly updated Total Fees: ' . $rounded_net_price . ' <br />';
    }
  }



  public function save_freshmen_retention_rate( $college_id = '', $retention_rate = '' ) {
    if ( ! $college_id ) return false;
    if ( ! $retention_rate ) return false;

    // echo 'Rtention Rate: ' . $retention_rate . ' <br />';

    $return = wp_set_post_terms( $college_id, $retention_rate, 'retention', false );

    if ( $return ) {
      echo 'Successfuly updated Retention Rate: ' . $retention_rate . ' <br />';
    }

  }


  public function save_top_10_of_class( $college_id = '', $top_10_of_class = '' ) {
    if ( ! $college_id ) return false;
    if ( ! $top_10_of_class ) return false;

    // echo 'Top 10 of HS class: ' . $top_10_of_class . ' <br />';

    $return = wp_set_post_terms( $college_id, $top_10_of_class, 'top_10_hs', false );

    if ( $return ) {
      echo 'Successfuly updated Top 10 of HS class: ' . $top_10_of_class . ' <br />';
    }

  }


  public function save_acceptance_rate( $college_id = '', $percent_admitted = '' ) {
    if ( ! $college_id ) return false;
    if ( ! $percent_admitted ) return false;

    // echo 'Acceptance Rate: ' . $percent_admitted . ' <br />';

    $return = wp_set_post_terms( $college_id, $percent_admitted, 'acceptance', false );

    if ( $return ) {
      echo 'Successfuly updated Acceptance Rate: ' . $percent_admitted . ' <br />';
    }

  }


  public function save_graduation_rate( $college_id = '', $graduation_rate = '' ) {
    if ( ! $college_id ) return false;
    if ( ! $graduation_rate ) return false;

    // echo '4 Year Graduation Rate: ' . $graduation_rate . ' <br />';

    $return = wp_set_post_terms( $college_id, $graduation_rate, 'graduation', false );

    if ( $return ) {
      echo 'Successfuly updated 4 Year Graduation Rate: ' . $graduation_rate . ' <br />';
    }

  }
  public function save_test_data( $college_id, $sat_75, $sat_25, $act_75, $act_25 ) {
    if ( ! $college_id ) return false;
    // if ( ! $graduation_rate ) return false;
    //
    echo 'SAT 75: ' . $sat_75 . ' <br />';
    echo 'SAT 25: ' . $sat_25 . ' <br />';
    echo 'ACT 75: ' . $act_75 . ' <br />';
    echo 'ACT 25: ' . $act_25 . ' <br />';

    if ( $sat_75 || $sat_25 ) {
      $this -> save_test_type_data( $college_id, 'SAT', $sat_75, $sat_25 );
    } elseif ( $act_75 || $sat_25 ) {
      $this -> save_test_type_data( $college_id, 'ACT', $act_75, $act_25 );
    }

    // // echo '4 Year Graduation Rate: ' . $graduation_rate . ' <br />';
    //
    // $return = wp_set_post_terms( $college_id, $graduation_rate, 'graduation', false );
    //
    // if ( $return ) {
    //   echo 'Successfuly updated 4 Year Graduation Rate: ' . $graduation_rate . ' <br />';
    // }

  }

  public function save_test_type_data( $college_id, $type, $val_75, $val_25 ) {
    if ( ! $college_id ) return false;

    echo 'We have Data for ' . $type . '<br />';

    // echo '4 Year Graduation Rate: ' . $graduation_rate . ' <br />';

    $return = wp_set_post_terms( $college_id, $type, 'test_type', false );

    if ( $return ) {
      echo 'Successfuly updated Test Type: ' . $type . ' <br />';
    }
    if ( $val_75 ) {
        $return = wp_set_post_terms( $college_id, $val_75, 'test_75', false );
        if ( $return ) {
          echo 'Successfuly updated ' . $type . ' 75%: ' . $val_75 . ' <br />';
        }
    }
    if ( $val_25 ) {
        $return = wp_set_post_terms( $college_id, $val_25, 'test_25', false );
        if ( $return ) {
          echo 'Successfuly updated ' . $type . ' 25%: ' . $val_25 . ' <br />';
        }
    }


  }





} // End class
