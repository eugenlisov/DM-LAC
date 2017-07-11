<?php

function dm_lac_register_style_and_scripts() {
  wp_register_style( 'dm-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', 'news-pro-theme' );
  wp_register_script( 'dm-bootstrap' , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), '', true  );

  // FA is already enqueued by MM, not really neccesary
  wp_register_style( 'dm-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', 'news-pro-theme' );

  wp_register_style( 'dm-inspinia', plugins_url() . '/devmaverick-surveys' . '/assets/css/inspinia-style.css', false );
  wp_register_style( 'dm-inspinia-light', plugins_url() . '/devmaverick-surveys' . '/assets/css/dm-inspinia.css', false );
  wp_register_style( 'dm-source-sans-pro', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300', false );






  wp_register_style( 'dm-custom', plugins_url() . '/devmaverick-surveys/assets/css/dm-custom.css', false );
  wp_register_script( 'dm-all-site-scripts',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-scripts.js', 'simple-locator', '', true );
  wp_register_script( 'dm-compare-colleges',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-compare-colleges.js', '', '', true );


  //Enqueue the script that's open the modal after ading to favorites
  wp_register_script( 'dm-colleges',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-colleges.js', array(), '', true);
  wp_register_script( 'dm-landing-page',  plugins_url() . '/devmaverick-surveys/assets/js/front/dm-landing-page.js', array(), '', true);

  // Tabs section styles and scripts
  wp_register_script( 'dm-tabs', plugins_url() . '/devmaverick-surveys/assets/js/tabs.js', array(), '1.0.0', true );

  // Chartist
  wp_register_style( 'dm-chartist', SURVEYS_ASSETS_URL . '/js/front/chartist-js/chartist.min.css', false );
  wp_register_script( 'dm-chartist', SURVEYS_ASSETS_URL . '/js/front/chartist-js/chartist.min.js', false );

  // CountUp JS
  wp_register_script( 'dm-countup', SURVEYS_ASSETS_URL . '/lib/countUp.min.js', false );



  //Select 2
  wp_register_style( 'dm-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', false );
  wp_register_script( 'dm-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ), '1.0.0', true  );


}
add_action('wp_enqueue_scripts', 'dm_lac_register_style_and_scripts');






function dm_lac_enqueue_style_and_scripts() {

  if( is_page( PAGE_COMPARE_COLLEGES ) ) {
    wp_enqueue_style( 'dm-select2' );
    wp_enqueue_script( 'dm-select2' );

    wp_enqueue_script( 'dm-compare-colleges' );

  }

  if ( is_page( PAGE_MY_COLLEGES ) || is_page( PAGE_PREMIUM_HOME ) || is_page() ) {
    wp_enqueue_style( 'dm-bootstrap' );
    wp_enqueue_script( 'dm-bootstrap' );
    wp_enqueue_style( 'dm-inspinia-light' );
  }

  // wp_enqueue_style('dm-tabs');
  wp_enqueue_style( 'dm-custom' );
  wp_enqueue_style( 'dm-source-sans-pro' );
  wp_enqueue_script( 'dm-countup' );
  wp_enqueue_script( 'dm-all-site-scripts' );
  wp_enqueue_script( 'dm-colleges' );



  //  Chartist
  wp_enqueue_style( 'dm-chartist' );
  wp_enqueue_script( 'dm-chartist' );

  // if (is_page( PAGE_GET_PREMIUM )) {
    wp_enqueue_script('dm-landing-page');
  // }



}
add_action('wp_enqueue_scripts', 'dm_lac_enqueue_style_and_scripts');

function dm_lac_admin_script() {
  wp_register_script( 'dm-comparator-setup', SURVEYS_ASSETS_URL . '/js/back/dm-comparator-setup.js', false );

  //Select 2
  wp_register_style( 'dm-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', false );
  wp_register_script( 'dm-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', false );

  $page = htmlspecialchars($_GET["page"]);
  if ( $page == 'dm-surveys-setup-comparator' ) {
    echo 'sugiuc';

    wp_enqueue_style( 'dm-select2' );
    wp_enqueue_script( 'dm-select2' );
    wp_enqueue_script( 'dm-comparator-setup' );

  }

}

add_action( 'admin_enqueue_scripts', 'dm_lac_admin_script' );
 ?>
