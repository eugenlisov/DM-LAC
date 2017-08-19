<?php
/**
 * Use like this: $dm_call_to_action = new DM_CallToAction;
 */
class DM_CallToAction {

  public function default_tab_call_to_action() {


    $return = '<div class="dm-call-to-action">
                <div class="col-xs-12 col-sm-6 col-md-6">
                  <img src="' . SURVEYS_ASSETS_URL . '/images/call-to-action-333x333.png">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="dm-call-to-action-header">
                    <h2>Get Our In Depth Analysis of Liberal Arts Colleges</h2>
                  </div>
                  <div class="dm-call-to-action-body">
                    The smartest way to cut through the college marketing hype and pick a college that is a perfect fit for your child is to become a Liberal Arts Insider member. Our research reveals inside information that colleges will never tell you.
                  </div>
                  <div class="dm-call-to-action-buttons">
                    <a href="' . get_permalink( PAGE_PREMIUM_VSL2 ) . '" class="get-premium"><i class="fa fa-user" aria-hidden="true"></i> Learn More</a>
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

      $cta = '<div class="dm-content-call-to-action" ' . $style . '>
                    <div class="dm-content-call-to-action-content">
                        <h3>Want to learn what ' . $respondents_count . ' ' . get_the_title() . ' students told us?</h3>
                        <h4>GET <span>THE TRUTH</span> ABOUT LIFE ON CAMPUS.</h4>
                        <a class="dm-click-to-upgrade" href="' . PAGE_GET_PREMIUM_PERMALINK . '">Click here to upgrade</a>
                    </div>
                </div>';


        $denied_shortcode   = "[MM_Access_Decision access='false']" . $cta . "[/MM_Access_Decision]";

        $return = do_shortcode( $denied_shortcode );

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
