<?php
wp_enqueue_style('dm-schools-style', plugins_url() . '/devmaverick-surveys/assets/css/back/colleges.css');
wp_enqueue_style('fontawesome', 'http:////netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css', '', '4.5.0', 'all');
wp_enqueue_script('dm-schools-script', plugins_url() . '/devmaverick-surveys/assets/js/back/colleges.js', array(), '1.0.0', true );


 ?>

 <div class="wrap">
   <h1>Colleges</h1>


   <?php

   global $wpdb;

  //  $start = microtime(true);
   //
  //  // Get the data from the real question table
  //  $sql_unique_ipeds = 'SELECT DISTINCT(school_name), iped FROM `dm_survey_responses`';
  //  $unique_ipeds = $wpdb->get_results( $sql_unique_ipeds );
   //
  //  echo '<pre>';
  //  print_r($unique_ipeds);
  //  echo '</pre>';
   //
  //  $time_elapsed_secs = microtime(true) - $start;
  //  echo '<br />Time elapsed on this: ' . $time_elapsed_secs;

  $start = microtime(true);

   $unique_ipeds        =  get_unique_schools_by_ipeds();
   $unique_contact_data =  get_unique_contact_data();

  //  echo '<pre>';
  //  print_r($unique_ipeds);
  //  echo '</pre>';
  //
   $test = calculate_averages_by_iped( 138600 );

    // echo '<pre>';
    // print_r($test);
    // echo '</pre>';

    ?>
    <!-- <select>
      <?php
      foreach ($unique_ipeds as $key => $value) {
        $iped         = $key;
        $school_name  = $value;
        echo '<option iped="' . $iped . '" value="' . $iped . '">' . $school_name . '</option>';
      }
       ?>
    </select> -->
   <form method="post" action="options.php">


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

<table>
  <tr>
    <th>No.</th>
    <th>College Profile</th>
    <th>IPED</th>
    <th>Actions</th>
    <th>Contact data</th>
    <th>Actions</th>
    <th class="dm-recalculate-column"><span class="recalculate-averages"> <i class="fa fa-refresh" aria-hidden="true"></i> Recalculate averages</span></th>
  </tr>

<?php


     $args = array( 'post_type' => 'schools',
                    'posts_per_page' => 300,
                    'post_status' => 'publish',
                    'orderby'=> 'title',
                    'order' => 'ASC'
                  );
      $loop = new WP_Query( $args );
      $q = 1;
      while ( $loop->have_posts() ) : $loop->the_post();

      $current_college_id = get_the_ID();

      $current_college_iped = get_post_meta( $current_college_id, 'school_iped', true );
      $current_college_highrise_id = get_post_meta( $current_college_id, 'school_highrise_id', true );
      // echo $current_college_iped . '<br />';

      // should also get the current custom post for the iped, to see it's allright.
      // # here

      $current_college_name = get_the_title();

        echo '<tr college-id="' . $current_college_id . '">
          <td>' . $q . '</td>
          <td><a href="' . get_the_permalink() . '" target="_blank">' . $current_college_name . '</a></td>
          <td class="select-school-iped trigger-select-school-iped">';
          ?>
          <select>
            <option disabled selected value> -- select a school -- </option>
        <?php
            $match_found = 0;
            $mismatch = 0;
            foreach ($unique_ipeds as $iped => $school_name) {
              $selected = ($current_college_iped == $iped) ? 'selected="selected"' : '';
              if ($selected != '') {
                $match_found = 1;
                if ($current_college_name != $school_name) {
                  $mismatch = 1;
                } else {
                  $mismatch = 0;
                }
              }

              echo '<option iped="' . $iped . '" value="' . $iped . '" ' . $selected . '>' . $school_name . '</option>';
            }
             ?>
          </select>
        <?php
          echo'
            </td>
            <td>';
            if ($match_found != 0) {
              echo '<span class="remove trigger-remove-iped"><i class="fa fa-times" aria-hidden="true"></i></span> ';
              if ($mismatch != 0) {
                echo '<span class="mismatch">Possible mismatch <i class="fa fa-flag" aria-hidden="true"></i> </span> ';
              }
            } elseif (in_array($current_college_name, $unique_ipeds)) {
              echo 'Set <span class="suggestion trigger-iped-suggestion" iped="' . array_search($current_college_name, $unique_ipeds) . '">' . $current_college_name . ' <i class="fa fa-pencil" aria-hidden="true"></i> </span>';
            }
          echo '</td>

          <td class="select-school-contact trigger-select-school-contact">';
          ?>
          <select>
            <option disabled selected value> -- select a school -- </option>
            <?php
            $match_found = 0;
            $mismatch = 0;
            foreach ($unique_contact_data  as $highrise_id => $school_name) {
              $selected = ($current_college_highrise_id == $highrise_id) ? 'selected="selected"' : '';
              if ($selected != '') {
                $match_found = 1;
                if ($current_college_name != $school_name) {
                  $mismatch = 1;
                } else {
                  $mismatch = 0;
                }
              }

              echo '<option highrise-id="' . $highrise_id . '" value="' . $highrise_id . '" ' . $selected . '>' . $school_name . '</option>';
            }
             ?>
          </select>

          <?php

          echo '</td>
          <td>';
          // echo 'MISMATCH VALUE = ' . $mismatch . '<br />';
          // echo '$current_college_name = ' . $current_college_name . '<br />';
          // echo '$school_name = ' . $school_name . '<br />';

          // $current_college_name != $school_name


          if ($match_found != 0) {
            echo '<span class="remove trigger-remove-highrise-id"><i class="fa fa-times" aria-hidden="true"></i></span> ';
            if ($mismatch != 0) {
              echo '<span class="mismatch">Possible mismatch <i class="fa fa-flag" aria-hidden="true"></i> </span> ';
            }
          } elseif (in_array($current_college_name, $unique_contact_data )) {
            echo 'Set <span class="suggestion trigger-contact-data-suggestion" highrise-id="' . array_search($current_college_name, $unique_contact_data) . '">' . $current_college_name . ' <i class="fa fa-pencil" aria-hidden="true"></i> </span>';
          }
          echo '</td>


          <td class="recalculate">';
          if (in_array($current_college_name, $unique_ipeds)) {
            if ($match_found != 0) {
              // echo '<span class="recalculate-averages" iped="' . array_search($current_college_name, $unique_ipeds) . '"> <i class="fa fa-refresh" aria-hidden="true"></i> Recalculate averages</span>';
            }
          }
          echo '</td>
        </tr>';
        $q++;

      endwhile;

   ?>


</table>


   </form>
   <?php
   $time_elapsed_secs = microtime(true) - $start;
   echo '<br />Time elapsed on this: ' . $time_elapsed_secs;

    ?>
</div>
