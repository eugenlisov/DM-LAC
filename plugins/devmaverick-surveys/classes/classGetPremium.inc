<?php
/**
 * Use like this: $dm_get_premium = new DM_GetPremium;
 */
class DM_GetPremium {

  public function get_premium_header() {
    $return = '<div class="site-container dm-checkout-header dm-get-premium-header">
                  <div class="col-sm-6 dm-column-left">

                    <div class="title-area">
                    <h1 class="site-title" itemprop="headline">' . get_bloginfo( 'name' ) . '</h1>
                    <p class="site-description" itemprop="description">the Complete Guide to Liberal Arts Colleges</p>
                    </div>

                  </div>
                  <div class="col-sm-6 dm-column-right">
                  ' . get_field( 'dm_get_premium_header_right_text' ) . '
                  </div>
              </div>';

    $premium_headline = get_field( 'dm_get_premium_headline' );

    if ( $premium_headline ) {
        $return .= '<div class="site-container dm-checkout-header dm-get-premium-headline">
                      <h2>' . get_field( 'dm_get_premium_headline' ) . '</h2>
                    </div>';
    }

    return $return;
  }


}
