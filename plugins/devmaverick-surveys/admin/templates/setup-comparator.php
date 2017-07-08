<?php

$dm_comparator = new DM_Comparator;
// $dm_comparator -> update_comparator_data_for_all_schools();
// $settigns = get_option( 'dm_college_comparison_settings' );
//
// echo '<pre>';
// print_r ( $settigns );
// echo '</pre>';
 ?>

 <div class="wrap">
   <h1>Set Up Comparator</h1>
   <form method="post" action="options.php">

   </form>

   <?php
    echo $dm_comparator -> setup_question_block();


    // $college_id = 1568; //Agness scott
    // echo $dm_comparator -> calculate_college_comparator_data( $college_id );

    ?>
</div>



<style>
.dm-setup-comparator-questions {
  overflow: hidden;
}
.dm-setup-comparator-question-row {
  clear: both;
  overflow: hidden;
}
.dm-setup-comparator-question-row.dm-title-row {
  font-weight: bold;
  margin-bottom: 10px;
}
.dm-setup-comparator-question-row > div {
  float: left;
  width: 25%;
  min-height: 10px;
}
.dm-setup-comparator-question-row > div > input,
.dm-setup-comparator-question-row > div > select {
  width: 100%;
}
.dm-save-button {
  margin-top: 10px !important;
}

</style>
