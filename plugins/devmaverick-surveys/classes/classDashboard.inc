<?php
/**
 * Use like this: $dm_dashboard = new DM_Dashboard;
 */
class DM_Dashboard {

  public function dashboard_links() {

  	$my_colleges_count = count( unserialize( CURRENT_USER_FAVORITE_SCHOOLS ) );
  	$return =  '<div class="dm-dashboard-links">';

  	$return .= '<div class="row">
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

  	$return .=  '</div>';

  	return $return;
  }

} // End class
