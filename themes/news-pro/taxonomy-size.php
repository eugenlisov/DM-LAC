<?php get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div class="content-sidebar-wrap">

<?php genesis_before_content(); ?>
<div class="content hfeed">


<?php $args = array(
   'meta_key' => 'state',  // Use your own key here
//   'orderby' => 'meta_value',
   'orderby' => 'meta_value ASC, title ASC',
   'order'	=> 'ASC'
);
query_posts(array_merge( $wp_query->query,$args )); ?>

<?php genesis_loop(); ?>
<?php genesis_after_loop(); ?>

</div><!-- end #content -->
<?php genesis_after_content(); ?>

</div><!-- end #content-sidebar-wrap -->
<?php genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>
