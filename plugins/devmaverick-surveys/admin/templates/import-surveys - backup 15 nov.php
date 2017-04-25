<?php


 ?>

 <div class="wrap">
   <h1>Import Surveys</h1>

   <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
     <input type="file" name="file">
     <input type="submit" name="btn_submit" value="Upload File" />
   </form>

   <?php
   if ($_FILES) {

     global $wpdb;

     // Get the data from the real question table
     $sql_csv_associations_data = 'SELECT * from dm_survey_all_questions WHERE NOT other = "yes"';
     $csv_associations_data = $wpdb->get_results( $sql_csv_associations_data );

     echo '<pre> CSV Association data';
     print_r($csv_associations_data);
     echo '</pre>';

     exit;

     // Get the data from the options table
     $sql_get_survey_option = 'SELECT * from dm_survey_options';
     $survey_options = $wpdb->get_results( $sql_get_survey_option );

     // Associate the options to each of the questions in the 'All' table;
     $survey_options_by_question = array();
     foreach ($survey_options as $key => $option) {
       $survey_options_by_question[$option -> q_id_all][] = $option;
     }

    //  echo '<pre> Survey Options';
    //  print_r($survey_options_by_question);
    //  echo '</pre>';


     // Read the CSV file and save to a two dimensional array.


     $fh = fopen($_FILES['file']['tmp_name'], 'r+');
     $lines = array();
     while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
       $lines[] = $row;
     }

  //    	ini_set('display_startup_errors', 1);
	// ini_set('display_errors', 1);
	// error_reporting(-1);

    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';



        foreach ($lines as $key => $line) {

          if ($key == 0) { continue; }

          //Reset all the time
          $answer = array();
          $score = array();

          // $iped =  $line[0];
          // $respondent_id =  $line[1];
          // $collector_id =  $line[2];

          // Go through the list grabbed from the All table and read the values from the csv, based on the column in the CSV

          foreach ($csv_associations_data as $key => $value) {

            $iped =  $line[0];
            $respondent_id =  $line[1];
            $collector_id =  $line[2];

            echo 'IPED is ' . $iped . '<br />';
            echo 'Respondent ID is ' . $respondent_id . '<br />';
            echo 'Collector ID is ' . $collector_id . '<br /><br />';


            $answer[ $value -> id ] = $line[ $value ->  csv_column ];

            // Calculate the score for each of the otion by looping through the options array.
            foreach ($survey_options_by_question[$value -> id] as $key => $q_option) {
              // echo trim( $q_option -> option_text ) . '<br />';
              // echo trim( $line[ $value ->  csv_column ] ) . '<br />';

              if (trim($q_option -> option_text) == trim( $line[ $value ->  csv_column ] )) {

                // echo trim( $q_option -> option_text ) . '<br />';
                // echo trim( $line[ $value ->  csv_column ] ) . '<br />';

                $score[ $value -> id ] = $q_option -> option_score;

                // echo 'Jackpot <br />';

                echo 'Respondent ID is ' . $respondent_id . ', deci imi bag pl daca nu merge.<br />';
                // Insert the response to the database
                $result = $wpdb->insert(
                    	'dm_survey_responses',
                    	array(
                    		'iped'          => $line[0],
                        'school_name'   => $line[10],
                    		'respondent_id' => $line[1],
                        'q_all_number'  => $value -> id,
                        'response'      => $q_option -> option_text,
                        'q_score'       => $q_option -> option_score
                    	),
                    	array(
                    		'%d',
                    		'%s',
                        '%d',
                    		'%d',
                    		'%s',
                    		'%d'
                    	)
                    );
                    echo 'Insert results was ' . $result . '<br /><br />';

                break;
              }
            }
          }

          // echo '<pre>Answer';
          // print_r($answer);
          // echo '</pre><br />';
          // //
          // echo '<pre>Score';
          // print_r($score);
          // echo '</pre><br />';






        }

     exit;

     global $wpdb;

     foreach ($lines as $key => $line) {
       if ($key == 0) continue;

       $iped        = $line[0];
       $q_number    = $line[1];
       $q_text      = $line[2];
       $school_ave  = $line[3];

      //  echo 'Key = ' . $key . '<br />';

      // 1. First try to update the existing values.
      // If there is none, the function will return false and we'll then insert the new value.


      $sql_count = ' SELECT COUNT(*) as count FROM dm_school_averages
                      WHERE iped = ' . $iped . '
                        AND q_number = ' . $q_number;


      $count =      $wpdb->get_row( $sql_count );
      $count = $count -> count;

      // $mylink = $wpdb->get_row( $sql_count );

                        // echo $sql_count . '<br /><br />';
      //
      // echo '<pre>';
      // print_r($count);
      // echo '</pre>';


      $sql_update = ' UPDATE dm_school_averages
                      SET school_ave = ' . $school_ave . '
                      WHERE iped = ' . $iped . '
                        AND q_number = ' . $q_number;

      $sql_insert = 'INSERT
                    INTO dm_school_averages (iped, q_number, school_ave)
                    VALUES (' . $iped . ', ' . $q_number . ', ' . $school_ave . ')';

      if ($count > 0) { // Update
        // echo $sql_update . '<br />';
        $result = $wpdb->query( $sql_update );
        $message_ok     = 'Successfully updated the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
        $message_false  = 'There was an error updating the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
        $message_nochange  = 'There was no change for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
      } else { // Insert
        // echo $sql_update . '<br />';
        $result = $wpdb->query( $sql_insert );
        $message_ok     = 'Successfully inserted the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
      }

      if (false === $result) {
        echo $message_false . '<br />';
      } elseif ($result == 0) {  // If the return was 0, not FALSE, display that there was no change.
        echo $message_nochange . '<br />';
      } else {
        echo $message_ok . '<br />';
      }

      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';

    }

    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';

   }
   ?>


   <form method="post" action="options.php">



   </form>
</div>
