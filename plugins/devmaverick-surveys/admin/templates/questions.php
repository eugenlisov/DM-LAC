<?php

wp_enqueue_script('dm-questions-script', plugins_url() . '/devmaverick-surveys/assets/js/admin-questions.js', array(), '1.0.0', true );

 ?>

 <div class="wrap">
   <h1>Survey Questions</h1>


   <table class="dm-questions-table">
     <tr>
       <th>No.</th>
       <th>Question (short)</th>
       <th>Question (full text)</th>
       <th>LAC Average</th>
       <th>Tab Narrative</th>
       <th>Comparisons Narrative</th>
     </tr>

   <?php


   // Now display all the values saved in the database;
   global $wpdb;
   $sql_get_averages      = 'SELECT * from dm_survey_questions';
   $sql_get_all_question  = 'SELECT * from dm_survey_all_questions';

   $questions =      $wpdb->get_results( $sql_get_all_question );

  //  echo '<pre>';
  //  print_r($questions);
  //  echo '</pre>';

   foreach ($questions as $key => $question) {
     echo '<tr question-id="' . $question -> id . '">
       <td>' . $question -> q_number . '</td>
       <td>' . $question -> q_short_text . '</td>
       <td>' . $question -> q_text . '</td>
       <td>...</td>

       <td><textarea rows="2" cols="50" content-type="tab-narrative">' . $question -> tab_narrative .'</textarea></td>
       <td><textarea rows="2" cols="50" content-type="comparison-narrative">' . $question -> comparison_narrative .'</textarea></td>

     </tr>';
     $q++;
   }



// }

      ?>


   </table>




   <form method="post" action="options.php">



   </form>
</div>
