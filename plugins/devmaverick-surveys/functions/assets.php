<?php

function dm_lac_register_style_and_scripts() {
  wp_enqueue_style( 'dm-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', 'news-pro-theme' );
  wp_register_script( 'dm-bootstrap' , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), '', true  );

  wp_register_style('dm-inspinia', plugins_url() . '/devmaverick-surveys' . '/assets/css/inspinia-style.css', false );

}
add_action('wp_enqueue_scripts', 'dm_lac_register_style_and_scripts');






function dm_lac_enqueue_style_and_scripts() {

  if ( is_page( PAGE_MY_COLLEGES ) ) {
    wp_enqueue_style( 'dm-bootstrap' );
    wp_enqueue_style( 'dm-inspinia' );
  }

}
add_action('wp_enqueue_scripts', 'dm_lac_enqueue_style_and_scripts');
 ?>
