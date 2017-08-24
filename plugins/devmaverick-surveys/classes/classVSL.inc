<?php
/**
 * Use like this: $dm_vsl = new DM_VSL;
 */

class DM_VSL {

  public function cta_btn() {

    $cta_button_text = get_field( 'cta_button_text' );
    $cta_button_delay = get_field( 'cta_button_delay' );


    // echo '<pre>';
    // print_r( $parameters );
    // echo '</pre>';
    $parameters_string = $this -> get_vsl_affiliate_parameters();

    $visible_class = ( $cta_button_delay <= 0) ? 'dm-vsl-cta-btn-visible' : '';

    $return = '<div class="dm-vsl-cta-btn ' . $visible_class . '">
                <a href="' . get_permalink( PAGE_PRICING ) . $parameters_string . '" class="btn btn-lg btn-warning">' . $cta_button_text . '</a>
              <div>';


    if ( $cta_button_delay >= 0 ) {

      $return .= '<script>
                  var ctaBtnDelay = ' . $cta_button_delay . ';
                </script>';

    }

    return $return;

  }

  public function get_vsl_affiliate_parameters() {

    $affiliate_id = get_field( 'dm_affiliate_id' );
    $affiliate_sid = get_field( 'dm_affiliate_sid' );

    if ( $affiliate_id ) {
      $parameters[] = 'affid=' . $affiliate_id;
    }
    if ( $affiliate_sid ) {
      $parameters[] = 'sid=' . $affiliate_sid;
    }
    if ( $parameters ) {
      $parameters_string = '?' . implode( "&", $parameters );
      return $parameters_string;
    } else {
      return '';
    }

  }

} // End Class
 ?>
