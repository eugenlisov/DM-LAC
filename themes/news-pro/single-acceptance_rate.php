<?php
/*
Template Name: Acceptance Rates
*/
?>

<?php get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div class="content-sidebar-wrap">

<?php genesis_before_content(); ?>
<div class="content hfeed">

<?php genesis_before_loop();?>

<?php genesis_loop(); ?>
<?php

// echo do_shortcode( '[dm-acceptance-rate]' );

 ?>
<?php genesis_after_loop(); ?>

</div><!-- end #content -->




</div><!-- end #content-sidebar-wrap -->
<?php get_sidebar(); ?>
<?php //genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>
