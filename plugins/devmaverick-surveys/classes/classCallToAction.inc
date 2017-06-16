<?php
/**
 * Use like this: $dm_call_to_action = new DM_CallToAction;
 */
class DM_CallToAction {

  public function default_tab_call_to_action() {


    $return = '<div class="dm-call-to-action">
                <div class="col-md-6">
                  <img src="' . SURVEYS_ASSETS_URL . '/images/call-to-action.png">
                </div>
                <div class="col-md-6">
                  <div class="dm-call-to-action-header">
                    <h2>Get Our In Depth Analysis of Liberal Arts Colleges</h2>
                  </div>
                  <div class="dm-call-to-action-body">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel condimentum ex. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc vulputate vehicula convallis.
                  </div>
                  <div class="dm-call-to-action-buttons">
                    <a href="" class="get-drip trigger-call-top-action-modal"><i class="fa fa-area-chart" aria-hidden="true"></i> Get our reports</a>

                    <a href="' . PAGE_GET_PREMIUM_PERMALINK . '" class="get-premium"><i class="fa fa-user" aria-hidden="true"></i> Get Premium</a>
                    </div>
                </div>
              </div>';

      return $return;
    }


    public function default_content_call_to_action() {

      $dm_school = new DM_School;
      $respondents_count = $dm_school -> get_respondents_count( get_the_ID() );

      if ( ! $respondents_count ) {
        return false;
      }

      $style = 'style="background-image: url( ' . SURVEYS_ASSETS_URL . '/images/call-to-action-background.png )"';

      $return = '<div class="dm-content-call-to-action" ' . $style . '>
                    <div class="dm-content-call-to-action-content">
                        <h3>Want to learn what ' . $respondents_count . ' ' . get_the_title() . ' students told us?</h3>
                        <h4>GET <span>THE TRUTH</span> ABOUT LIFE ON CAMPUS.</h4>
                        <a class="dm-click-to-upgrade" href="' . get_permalink( PAGE_GET_PREMIUM ) . '?rid=pv47iM">Click here to upgrade</a>
                    </div>
                </div>';

        return $return;
      }


} // End class


function dm_add_call_to_action( $content ) {

  if ( is_singular( 'schools' ) ) {

    $dm_call_to_action = new DM_CallToAction;

    $call_to_action = $dm_call_to_action -> default_content_call_to_action();

    $content .= $call_to_action;

  }
  return $content;
}


add_filter( 'the_content', 'dm_add_call_to_action', 10, 10 );
