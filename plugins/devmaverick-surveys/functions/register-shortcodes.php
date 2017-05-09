<?php
/*
* Initiate all shortcodes
*/
add_action( 'init', 'register_shortcodes');

/*
* Register all shortcodes
*/
function register_shortcodes(){
	add_shortcode('dm-school-tabs', 'dm_school_tabs');
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

	$my_colleges_count = count( unserialize( CURRENT_USER_FAVORITE_SCHOOLS ) );
	$return_string =  '<div class="dm-dashboard-links">';

	$return_string .= '<div class="row">
            <a href="' . get_permalink( PAGE_MY_ACCOUNT ) . '" class="col-lg-4">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> My Account </span>

                        </div>
                    </div>
                </div>
            </a>
            <a href="' . get_permalink( PAGE_MY_COLLEGES ) . '" class="col-lg-4">
                <div class="widget style1 lazur-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-university fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> My Colleges </span>
                            <h2 class="font-bold">' . $my_colleges_count . '</h2>
                        </div>
                    </div>
                </div>
            </a>
            <a href="' . get_permalink( CONTACT_PAGE ) . '" class="col-lg-4">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-envelope-o fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Contact </span>

                        </div>
                    </div>
                </div>
            </a>
        </div>';

	// $return_string .= '<a href="' . get_permalink( PAGE_MY_ACCOUNT ) . '" class="dm-dashboard-button col-md-3">My Account <i class="fa fa-user" aria-hidden="true"></i></a>';
	// $return_string .= '<a href="' . get_permalink( PAGE_MY_COLLEGES ) . '" class="dm-dashboard-button col-md-3">My Colleges <i class="fa fa-university" aria-hidden="true"></i></a>';
	// $return_string .= '<a href="' . get_permalink( CONTACT_PAGE ) . '" class="dm-dashboard-button col-md-3">Contact <i class="fa fa-envelope" aria-hidden="true"></i></a>';


	$return_string .=  '</div>';

	return $return_string;
}











 ?>
