<?php
/*
Template Name: Full Width
*/
?>

<?php get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div class="content-sidebar-wrap">

<?php genesis_before_content(); ?>
<div class="content-full hfeed">

<?php genesis_before_loop();?>
<?php genesis_loop(); ?>
<?php // echo do_shortcode(['dm-school-tabs']); ?>
<?php
if (is_singular( 'schools' )) {
  dm_school_tabs();
}
 ?>
<?php genesis_after_loop(); ?>




<div class="sidebar-bottom">

    <?php dynamic_sidebar('schools-bottom'); ?>

</div>

</div><!-- end #content -->



<?php /*?>
<?php genesis_after_content(); ?>
<?php */?>

</div><!-- end #content-sidebar-wrap -->
<?php //get_sidebar('schools'); ?>
<?php //genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>
