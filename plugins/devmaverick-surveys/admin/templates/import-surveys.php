<?php
  $dm_import_surveys = new DM_ImportSurveys;

 ?>

 <div class="wrap">
   <h1>Import Surveys</h1>

   <?php
   echo $dm_import_surveys -> import_surveys_form();

   echo $dm_import_surveys -> process_import_file();

    ?>

</div>
