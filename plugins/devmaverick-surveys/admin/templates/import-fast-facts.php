<?php
  $dm_import_fast_facts = new DM_ImportFastFacts;

 ?>

 <div class="wrap">
   <h1>Import Fast Facts</h1>

   <?php

   echo '<pre>';
   print_r( $return );
   echo '</pre>';


   echo $dm_import_fast_facts -> import_surveys_form();

   echo $dm_import_fast_facts -> process_import_file();

    ?>

</div>
