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
  width: 24%;
  min-height: 10px;
  padding: 1px 0.5%;
}

.dm-setup-comparator-question-row > div.dm-pretty-response {
  width: 15%;
}
.dm-setup-comparator-question-row > div.dm-category {
  width: 18%;
}
.dm-setup-comparator-question-row > div.dm-question {
  width: 20%;
}
.dm-setup-comparator-question-row > div.dm-response {
  width: 15%;
}
.dm-setup-comparator-question-row > div.dm-response-positive {
  width: 9%;
}
.dm-setup-comparator-question-row > div.dm-main-category {
  width: 10%;
}
.dm-setup-comparator-question-row > div.dm-big-six {
  width: 6%;
  text-align: center;
}
.dm-setup-comparator-question-row > div > input,
.dm-setup-comparator-question-row > div > select {
  width: 100%;
}
.dm-save-button {
  margin-top: 10px !important;
}

</style>
