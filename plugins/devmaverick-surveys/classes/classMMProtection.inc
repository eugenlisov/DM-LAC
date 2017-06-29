<?php
/**
 * Making use of this Wrapper: https://cristianpontes.github.io/zoho-crm-client-php/
 *
 * Use like this: $dm_mm_protection = new DM_MMProtection;
 *
 */
class DM_MMProtection {


  function get_all_school_ids() {

    $args = array(
      	'posts_per_page'   => 999,
      	'offset'           => 0,
      	'category'         => '',
      	'category_name'    => '',
      	'orderby'          => 'date',
      	'order'            => 'DESC',
      	'include'          => '',
      	'exclude'          => '',
      	'meta_key'         => '',
      	'meta_value'       => '',
      	'post_type'        => 'schools',
      	'post_mime_type'   => '',
      	'post_parent'      => '',
      	'author'	   => '',
      	'author_name'	   => '',
      	'post_status'      => 'publish',
      	'suppress_filters' => true
      );
      $posts_array = get_posts( $args );

      foreach ($posts_array as $key => $post) {
        // if ( in_array( $post -> ID, [3599, 1568, 1590, 3491, 1653] ) ) {
        //   continue;
        // }
        $ids[] = $post -> ID;
      }

      // echo '<pre>';
      // print_r ( $ids );
      // echo '</pre>';

      foreach ($ids as $key => $post_id) {
        $this -> add_school_protection( $post_id );
      }



  }


  public function add_school_protection( $post_id = '' ) {

		global $wpdb;

				$result = $wpdb->insert(
					'mm_posts_access',
					array(
						'post_id' => $post_id,
						'access_type' => 'member_type',
						'access_id' => 2,
						'days' => 0,
						'is_smart_content' => 0
					),
					array (
						'%d',
						'%s',
						'%d',
						'%d',
						'%d'
					)
				);

        if ( $result == 1 ) {
          echo 'Successfully added protection for ' . get_the_title( $post_id ) . '<br />';
        }
        // echo '<pre>';
        // print_R ( $result );
        // echo '</pre>';

	}



} // End class
