<?php

wp_enqueue_style('dm-schools-style', plugins_url() . '/devmaverick-surveys/assets/css/back/admin-tabs.css');

wp_enqueue_script('dm-schools-script', plugins_url() . '/devmaverick-surveys/assets/js/back/admin-tabs.js', array(), '1.0.0', true );

 ?>

 <div class="wrap">
   <h1>Tabs</h1>
   <style>
   label {
       font-weight: normal;
   }
   </style>

   <?php

  //  get_tabs_questions();

  $dm_question = new DM_Question;
  $all_questions_list = $dm_question -> get_all_questions_list();

   $tabs_list           = get_tabs_list();
  //  $all_questions_list  = get_all_questions_list();
   $tabs_questions      = get_tabs_questions();

  //  echo '<pre>';
  //  print_r($all_questions_list);
  //  echo '</pre>';
  //

  /**
   *
   * 1. First loop for the regular tabs
   *
   */

  echo '<h2>Regular Tabs</h2>';
  echo '<div class="dm-tabs-group">';

   foreach ($tabs_list as $key => $tab) {
     $tab_type = $tab -> type;

     if ($tab_type == 'overview' || $tab_type == 'comparison') {
       continue;
     }

      // echo '<pre>';
      // print_r($tab);
      // echo '</pre>';
     echo '<div tab-id="' . $tab -> id .'" class="col-md-4 dm-admin-tab dm-regular-tab">';
     echo ' <h3>' . $tab -> tab_name . '</h3>';

     echo '<div>';
     echo '<h4>Tab intro</h4>';
     echo '<textarea rows="4" cols="50" content-type="tab-intro" class="dm-tab-intro-textarea">' . $tab -> tab_intro .'</textarea>';

     if ($tab_type != 'overview' && $tab_type != 'comparison') {
       echo '<h4>Comparisons tab narrative</h4>';
       echo '<textarea rows="4" cols="50" content-type="comparison-narrative" class="dm-tab-comparison-textarea">' . $tab -> comp_section_narrative .'</textarea>';
     }

     if ($tab_type != 'overview') {
       echo '<h4>Protected content narrative</h4>';
       echo '<small>Will show up when current user doesn\'t have permission to see the current tab</small>';
       echo '<textarea rows="4" cols="50" content-type="protected-content-narrative" class="dm-tab-protected-textarea">' . $tab -> protected_narrative .'</textarea>';
     }


    echo '</div>';

    if ($tab_type == 'regular') {

      echo '<h4>Select the questions for the current tab</h4>';

        foreach ($all_questions_list as $key => $question) {

          if (!empty($tabs_questions[$tab -> id])) {
            $checked = ( in_array($question -> id, $tabs_questions[$tab -> id]) ) ? 'checked="checked"' : '';
            // if (in_array($question -> id, $tabs_questions[$tab -> id])) {
            //   echo 'bingo';
            // }
          }
          echo '<label><input type="checkbox" name="checkbox" class="dm-question" ' . $checked . ' value="' . $question -> id . '">' . $question -> q_number . ' - ' . $question -> q_short_text . '</label> <br />';
        }

      } // END if regular tab.

     echo '</div>';
   }
   echo '</div>';



   /**
    *
    * 2. Now loop for the overview and comparisons
    *
    */

  echo '<div class="dm-tabs-group">';
  echo '<h2>Overview and Comparisons Tabs</h2>';

    foreach ($tabs_list as $key => $tab) {
      $tab_type = $tab -> type;

      if ($tab_type == 'regular') {
        continue;
      }

       // echo '<pre>';
       // print_r($tab);
       // echo '</pre>';
      echo '<div tab-id="' . $tab -> id .'" style="width: 33.3%; float: left;" class="col-md-4 dm-admin-tab">';
      echo ' <h3>' . $tab -> tab_name . '</h3>';

      echo '<div>';
      echo '<h4>Tab intro</h4>';
      echo '<textarea rows="4" cols="50" content-type="tab-intro">' . $tab -> tab_intro .'</textarea>';

      if ($tab_type != 'overview' && $tab_type != 'comparison') {
        echo '<h4>Comparisons tab narrative</h4>';
        echo '<textarea rows="4" cols="50" content-type="comparison-narrative">' . $tab -> comp_section_narrative .'</textarea>';
      }

      if ($tab_type != 'overview') {
        echo '<h4>Protected content narrative</h4>';
        echo '<small>Will show up when current user doesn\'t have permission to see the current tab</small>';
        echo '<textarea rows="4" cols="50" content-type="protected-content-narrative">' . $tab -> protected_narrative .'</textarea>';
      }


     echo '</div>';

     if ($tab_type == 'comparison') {

       echo '<h4>Please select the questions for which the the current schools should be compared to the LAC average</h4>';

         foreach ($all_questions_list as $key => $question) {
           # code...
           if (!empty($tabs_questions[$tab -> id])) {
             $checked = ( in_array($question -> id, $tabs_questions[$tab -> id]) ) ? 'checked="checked"' : '';
             // if (in_array($question -> id, $tabs_questions[$tab -> id])) {
             //   echo 'bingo';
             // }
           }
           echo '<label><input type="checkbox" name="checkbox" class="dm-question" ' . $checked . ' value="' . $question -> id . '">' . $question -> q_number . ' - ' . $question -> q_short_text . '</label> <br />';
         }

       } // END if regular tab.

      echo '</div>';
    }

    echo '</div>';




    ?>
   <form method="post" action="options.php">



   </form>
</div>
