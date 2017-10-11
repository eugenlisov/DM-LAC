<?php
/*
Template Name: Schools
*/
?>

<?php get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div class="content-sidebar-wrap">

<?php genesis_before_content(); ?>
<div class="content hfeed">

<?php genesis_before_loop();?>
<?php genesis_loop(); ?>
<?php // echo do_shortcode(['dm-school-tabs']); ?>
<?php
// dm_school_tabs();
// $dm_tabs = new DM_Tabs;
// $dm_tabs -> tabs_section();
?>
<?php genesis_after_loop(); ?>

<?php

echo '<pre>';
print_r ( get_post_meta( get_the_ID(), 'test_sql_query', true ) );
echo '</pre>';

 ?>


<div class="sidebar-bottom">

    <?php dynamic_sidebar('schools-bottom'); ?>

</div>

</div><!-- end #content -->



<?php /*?>
<?php genesis_after_content(); ?>
<?php */?>

</div><!-- end #content-sidebar-wrap -->
<?php get_sidebar('schools'); ?>
<?php //genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>
