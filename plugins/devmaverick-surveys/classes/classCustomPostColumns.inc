<?php
class CustomPostTypesColumns {

    /**
    * Constructor
    */
    public function __construct() {

      add_action( 'manage_schools_posts_columns', array( $this, 'add_schools_columns' ) );
      add_action( 'manage_schools_posts_custom_column', array( $this, 'add_schools_columns_content' ), 10, 2  );

    }



    /**
     * Record
     */

    public function add_schools_columns( $defaults ) {
      unset( $defaults['date'] );

      $defaults['nps'] = 'NPS';
      $defaults['data_points'] = 'Data Points';
       return $defaults;
    }
    public function add_schools_columns_content( $column_name, $post_id ) {

      if ( 'nps' == $column_name ) {
        // $project_id = get_post_meta( $post_id, 'project_id', true );

        $dm_school = new DM_School;

        // echo 'Current school\'s NPS is: ';
        echo $dm_school -> get_nps( get_the_ID() );

        if ( ! $project_id ) {
          return;
        }

        echo get_the_title( $project_id ) . '<br />';

      }

      if ( 'data_points' == $column_name ) {
        // $project_id = get_post_meta( $post_id, 'project_id', true );

        $dm_school = new DM_School;

        // echo 'Current school\'s NPS is: ';
        echo $dm_school -> get_data_points_count( get_the_ID() );

        if ( ! $project_id ) {
          return;
        }

        // echo get_the_title( $project_id ) . '<br />';

      }

    }





} // End class


$custom_post_types_columns = new CustomPostTypesColumns;
