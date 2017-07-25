<?php
class DM_PluginSetup {


  /**
   * Constructor
   */
  public function __construct() {

      add_action( 'after_setup_theme', array( $this, 'setup_theme_thumb_sizes' ) );
  }


  public function setup_theme_thumb_sizes() {

  	add_image_size( 'Comparator_Thumb', 241, 175, TRUE );

  }


} // End class


$dm_plugin_setup = new DM_PluginSetup;
