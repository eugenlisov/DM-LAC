<?php


 ?>

 <div class="wrap">
   <h1>Import Averages</h1>
   <style>
   table {
       font-family: arial, sans-serif;
       border-collapse: collapse;
       width: 100%;
   }

   td, th {
       border: 1px solid #dddddd;
       text-align: left;
       padding: 8px;
   }

   tr:nth-child(even) {
       background-color: #dddddd;
   }
   </style>

   <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
     <input type="file" name="file">
     <input type="submit" name="btn_submit" value="Upload File" />
   </form>

   <?php

   global $wpdb;


   if ($_FILES) {

     // Read the CSV file and save to a two dimensional array.
     // 0. IPED
     // 1. Question Number
     // 2. Question text
     // 3. School Average

     $fh = fopen($_FILES['file']['tmp_name'], 'r+');
     $lines = array();
     while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
     	$lines[] = $row;
     }



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

   <table>
     <tr>
       <th>IPED</th>
       <th>Question Number</th>
       <th>School Average</th>
     </tr>
   <?php

   // Now display all the values saved in the database;
   $sql_get_averages = 'SELECT * from dm_school_averages';

   $averages =      $wpdb->get_results( $sql_get_averages );

    // echo '<pre>';
    // print_r($averages);
    // echo '</pre>';

    foreach ($averages as $key => $data) {
      echo '<tr>
        <td>' . $data-> iped . '</td>
        <td>' . $data-> q_number . '</td>
        <td>' . $data-> school_ave . '</td>
      </tr>';
    }


   ?>



   </table>






   <form method="post" action="options.php">



   </form>
</div>
