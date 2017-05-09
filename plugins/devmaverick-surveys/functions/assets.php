<?php

function dm_lac_register_style_and_scripts() {
  wp_register_style( 'dm-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', 'news-pro-theme' );
  wp_register_script( 'dm-bootstrap' , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), '', true  );

  // FA is already enqueued by MM, not really neccesary
  wp_register_style( 'dm-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', 'news-pro-theme' );

  wp_register_style('dm-inspinia', plugins_url() . '/devmaverick-surveys' . '/assets/css/inspinia-style.css', false );
  wp_register_style('dm-inspinia-light', plugins_url() . '/devmaverick-surveys' . '/assets/css/dm-inspinia.css', false );







  wp_register_style( 'dm-custom', plugins_url() . '/devmaverick-surveys/assets/css/dm-custom.css', false );
  wp_register_script( 'dm-all-site-scripts',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-scripts.js', 'simple-locator', '', true );

  //Enqueue the script that's open the modal after ading to favorites
  wp_register_script( 'dm-colleges',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-colleges.js', array(), '', true);
  wp_register_script( 'dm-landing-page',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-landing-page.js', array(), '', true);

  // Tabs section styles and scripts
  wp_register_script( 'dm-tabs', plugins_url() . '/devmaverick-surveys/assets/js/tabs.js', array(), '1.0.0', true );

}
add_action('wp_enqueue_scripts', 'dm_lac_register_style_and_scripts');






function dm_lac_enqueue_style_and_scripts() {

  if ( is_page( PAGE_MY_COLLEGES ) || is_page( PAGE_PREMIUM_HOME ) ) {
    wp_enqueue_style( 'dm-bootstrap' );
    wp_enqueue_script( 'dm-bootstrap' );
    wp_enqueue_style( 'dm-inspinia-light' );
  }

  // wp_enqueue_style('dm-tabs');
  wp_enqueue_style('dm-custom');
  wp_enqueue_script('dm-all-site-scripts');
  wp_enqueue_script('dm-colleges');

  // if (is_page( PAGE_GET_PREMIUM )) {
    wp_enqueue_script('dm-landing-page');
  // }

}
add_action('wp_enqueue_scripts', 'dm_lac_enqueue_style_and_scripts');
 ?>
