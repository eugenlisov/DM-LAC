<?php


 ?>

 <div class="wrap">
   <h1>Dev Page</h1>
   <p>To be used just by Eugen</p>


<?php

$dm_nps = new DM_NPS;
$nps = $dm_nps -> get_nps( $iped );
// $dm_nps -> process_all_nps();
//
$iped = 110413;


echo 'NPS for ' . $iped . ' is: ' . $nps;

 ?>
</div>
