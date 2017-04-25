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

      $start = microtime(true);

     // Get the data from the real question table
    //  $sql_csv_associations_data = 'SELECT * from dm_survey_all_questions WHERE NOT other = "yes"';
     $sql_csv_associations_data = 'SELECT * from dm_survey_all_questions WHERE visible <> "no"'; // Ignore question 38
     $csv_associations_data = $wpdb->get_results( $sql_csv_associations_data );

    //  echo '<pre> CSV Association data';
    //  print_r($csv_associations_data);
    //  echo '</pre>';

    //  exit;

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






    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';

        $unique_schools_array         = array();
        $unique_schools_strings_array = array();

        // Get the unique school names array from the database
        $sql_get_unique_schools = 'SELECT * from dm_school_ipeds';
        $unique_schools = $wpdb->get_results( $sql_get_unique_schools );
        if ($unique_schools) {
          foreach ($unique_schools as $key => $school) {
            $iped         = $school -> iped;
            $school_name  = $school -> school_name;
            $unique_schools_array[$iped] = $school_name;
          }
        }

        //  echo '<pre>';
        //  print_r($unique_schools_array);
        //  echo '</pre>';


        // 1. Clean up the surveys table
        $sql_empty_table = 'DELETE FROM dm_survey_responses';

        $result = $wpdb->query($sql_empty_table);
        if (!$result) {
          // echo 'ERROR! deleting the "dm_school_contacts" table<br />';
        } else {
          echo 'Successfully emptied the "dm_survey_responses" table. <br /><br />';
        }




        // 2. Now insery all the new data.


        foreach ($lines as $key => $line) {

          $iped           = $line[0];
          $respondent_id  = $line[1];
          $collector_id   = $line[2];
          $school_name    = '"' . $line[10] . '"';


          // Skip the  first row
          if ($key == 0) continue; // Slip first row.
          if (!$respondent_id) continue; // Pass on the empty  rows

          // Save the  school name in an array, as well as create the string to be used at the end when inserting.
          if (!in_array($line[10], $unique_schools_array)) {
            // echo 'vasile was here';
            $unique_schools_array[$iped] = $line[10]; // $line[10] is $school name
            $unique_schools_strings_array[$iped] = '(' . $iped .  ', ' . $school_name . ')';
          }
          // echo '<pre>';
          // print_r($unique_schools_array);
          // echo '</pre>';

          //Reset all the time
          $answer = array();
          $score = array();

          // $iped =  $line[0];
          // $respondent_id =  $line[1];
          // $collector_id =  $line[2];

          // Go through the list grabbed from the All table and read the values from the csv, based on the column in the CSV
          $response_sql_values = array();

          foreach ($csv_associations_data as $key_csv_association => $value) {


            $all_question_id = $value -> id;

            if ($value -> other == 'yes') {

              $other_response = '"' . $line[ $value ->  csv_column ] . '"';
              $score = '""';
              $option_text = '""';

              $row_values = array( $iped, $school_name, $respondent_id, $all_question_id, $option_text, $score, $other_response );

              // $row_values_string = '(' . implode(", ", $row_values) . ')';
              // $response_sql_values[] = $row_values_string;

            } else {


                        // Calculate the score for each of the option by looping through the options array.
                        if ($survey_options_by_question[$value -> id] ) {

                          foreach ($survey_options_by_question[$value -> id] as $key_survey_options => $q_option) {
                            // echo trim( $q_option -> option_text ) . '<br />';
                            // echo trim( $line[ $value ->  csv_column ] ) . '<br />';

                            if (trim($q_option -> option_text) == trim( $line[ $value ->  csv_column ] )) { // If there's a match

                              $score[ $value -> id ] = $q_option -> option_score;

                              $other_response = '""';
                              $score = $q_option -> option_score;
                              $option_text = '"' . $q_option -> option_text  . '"';

                              $row_values = array( $iped, $school_name, $respondent_id, $all_question_id, $option_text, $score, $other_response );


                            }
                          }

                        }

                  }

            if ($row_values) {
              $row_values_string = '(' . implode(", ", $row_values) . ')';

              // Some of the records tend to appear more times in the array. If they do, just don't add another one;
              if (!in_array($row_values_string, $response_sql_values)) {
                $response_sql_values[] = $row_values_string;
              } else {
                // echo 'The value is already in the array: ' . $row_values_string;
              }





            }



            $answer[ $value -> id ] = $line[ $value ->  csv_column ];


          }

          // HACK Trebuie gasita adevarata cauza a problemei.
          // Pentru acum, doar sterg prima valoare din array
          // echo 'Key values is: ' . $key . '<br />';
          if ($key > 0) {
            array_shift ( $response_sql_values );
          }

          // echo '<pre>';
          // print_r( $response_sql_values );
          // echo '</pre>';

          $response_sql_values_string = implode (", ", $response_sql_values);


          $sql_query = 'INSERT INTO dm_survey_responses (iped, school_name, respondent_id, q_all_number, response, q_score, response_other) VALUES ' . $response_sql_values_string;
          $result = $wpdb->query($sql_query);

          // echo '<pre> Response SQL values: ' . $key . '<br />';
          // print_r($sql_query);
          // echo '</pre>';
          // echo '<pre> Result of SQL query: <br />';
          // print_r($result);
          // echo '</pre>';
          if (!$result) {
            // echo 'ERROR! for ' . $respondent_id . '<br />';
          } else {
            echo 'Successfully added the response for Responder ID ' . $respondent_id . '<br />';
          }



          // echo '<pre>Answer';
          // print_r($answer);
          // echo '</pre><br />';
          // //
          // echo '<pre>Score';
          // print_r($score);
          // echo '</pre><br />';






        }
        // echo '<pre> Unique schools';
        // print_r($unique_schools_strings_array);
        // echo '</pre>';
        // If there are any schools that aren't already in the database, write them now
        if ($unique_schools_strings_array) {
          $unique_schools_string = implode (", ", $unique_schools_strings_array);

          // Save the schools to the  unique iped array table
          $sql_query_schools = 'INSERT INTO dm_school_ipeds (iped, school_name) VALUES ' . $unique_schools_string;
          $result = $wpdb->query($sql_query_schools);

          // echo '<pre> Unique schools result: ';
          // print_r($result);
          // echo '</pre>';
        }

        $time_elapsed_secs = microtime(true) - $start;

        // Trigger the averages recalculation
        dm_recalculate_averages();

        echo '<br />Time elapsed: ' . $time_elapsed_secs;

     exit;



   }
   ?>



</div>
