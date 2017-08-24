<?php
/**
 * Use like this: $dm_vsl = new DM_VSL;
 */

class DM_VSL {

  public function cta_btn() {

    $cta_button_text = get_field( 'cta_button_text' );
    $cta_button_delay = get_field( 'cta_button_delay' );

    $visible_class = ( $cta_button_delay <= 0) ? 'dm-vsl-cta-btn-visible' : '';

    $return = '<div class="dm-vsl-cta-btn ' . $visible_class . '">
                <a href="' . get_permalink( PAGE_PRICING ) . '" class="btn btn-lg btn-warning">' . $cta_button_text . '</a>
              <div>';


    if ( $cta_button_delay >= 0 ) {

      $return .= '<script>
                  var ctaBtnDelay = ' . $cta_button_delay . ';
                </script>';

    }

    return $return;

  }

} // End Class
 ?>
