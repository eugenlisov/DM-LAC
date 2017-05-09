<?php
/*
* Initiate all shortcodes
*/
add_action( 'init', 'register_shortcodes');

/*
* Register all shortcodes
*/
function register_shortcodes(){
	add_shortcode('dm-my-colleges', 'dm_my_colleges');
	add_shortcode('dm-dashboard-links', 'dm_dashboard_links');
	add_shortcode('dm-dashboard-my-colleges', 'dm_dashboard_my_colleges_widget');

}


function dm_my_colleges() {

	$dm_my_colleges = new DM_MyColleges;
	$return = $dm_my_colleges -> my_colleges_page();

	return $return;

}

/**
 *
 */
function dm_dashboard_my_colleges_widget( $limit = 999 ) {

	$dm_my_colleges = new DM_MyColleges;
	$return = $dm_my_colleges -> my_colleges_widget();

	return $return;

}



function dm_dashboard_links() {

	 $dm_dashboard = new DM_Dashboard;
	 $return = $dm_dashboard -> dashboard_links();

	 return $return;

}
