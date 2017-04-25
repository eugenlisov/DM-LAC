<?php


 ?>

 <div class="wrap">
   <h1>Import Contacts</h1>

   <p>Importing contacts will delete all the contact info stored in the database and write the new info.</p>
   <p>The association between the new data and the schools will remain in place as long as the the values in the "HighriseID" column in the import CSV are the same.</p>
   <p>After the import, go to the <a href="/wp-admin/admin.php?page=dm-surveys-colleges">Colleges</a> page and make sure you associate the new data with the collges.</p>


   <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
     <input type="file" name="file">
     <input type="submit" name="btn_submit" value="Upload File" />
   </form>



   <?php
   if ($_FILES) {

     global $wpdb;
     $start = microtime(true);


     $fh = fopen($_FILES['file']['tmp_name'], 'r+');
     $lines = array();
     while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
       $lines[] = $row;
     }

    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';


    // 1. Clean up the contacts table
    $sql_empty_table = 'DELETE FROM dm_school_contacts';

    $result = $wpdb->query($sql_empty_table);
    if (!$result) {
      // echo 'ERROR! deleting the "dm_school_contacts" table<br />';
    } else {
      echo 'Successfully emptied the "dm_school_contacts" table. <br /><br />';
    }


    // 2. Now insery all the new data.

        foreach ($lines as $key => $line) {

          $highrise_id    = $line[0];
          $full_name      = '"' . $line[2] . '"';
          // $first_name     = $line[3];
          // $last_name      = $line[4];
          $school_name        = '"' . $line[5] . '"';
          $title          = '"' . $line[6] . '"';
          $phone          = '"' . $line[7] . '"';
          $email          = '"' . $line[8] . '"';


          // Skip the  first row
          if ($key == 0) continue; // Slip first row.

          $insert_string = '(' . $highrise_id . ', ' . $full_name . ', ' . $school_name . ', ' . $title . ', ' . $phone . ', ' . $email . ')';

          $sql_query = 'INSERT INTO dm_school_contacts (highrise_id, full_name, school_name, title, phone, email) VALUES ' . $insert_string;

          // echo $sql_query . '<br /><br />';
          $result = $wpdb->query($sql_query);

          if (!$result) {
            echo 'ERROR! for ' . $school_name . '<br />';
          } else {
            echo 'Successfully added contact data for ' . $school_name . '<br />';
          }







        }

        $time_elapsed_secs = microtime(true) - $start;

        echo '<br />Time elapsed on this: ' . $time_elapsed_secs;




   }
   ?>



</div>
