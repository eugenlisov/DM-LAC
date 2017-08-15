<?php
/**
 * Use like this: $dm_checkout = new DM_Checkout;
 */
class DM_Checkout {

  public function checkout_page_content() {

    $return = '<div class="dm-checkout-page">
                <section class="dm-checkout-head-section">
                  <div class="dm-headline">
                    <h1>' . get_field( 'dm_checkout_headline' ) . '</h1>
                  </div>
                  <div class="dm-head-image">
                    <img src="' . get_field( 'dm_checkout_header_image' ) . '">
                  </div>
                </section>

                <section class="dm-checkout-body-section">';


    $return .= $this -> membermouse_form();


    $return .= '</section>

            </div>';

    return $return;
  }

  private function membermouse_form() {

    $return = '<div class="mm-checkoutContainer">
                [MM_Form type=\'checkout\']
                [MM_Form_Message type=\'error\']';


    $return .= '<div class="col-sm-6 dm-column-left">
                      <div class="dm-product-data-block">
                        <h2>' . get_field( 'dm_checkout_form_headline' ) . '</h2>
                        ' . $this -> block_product_data() . '
                      </div>
                      <div class="">
                        <h4><strong>Step #1:</strong> Contact information</h4>
                        ' . $this -> membermouse_account_information() . '
                      </div>
                      <div class="">
                        <h4><strong>Step #2:</strong> Billing Address</h4>
                        ' . $this -> membermouse_billing_information() . '
                      </div>
                  </div>

                  <div class="col-sm-6 dm-column-right">
                    <div class="dm-checkout-benefits">
                      ' . get_field( 'dm_checkout_benefits' ) . '
                    </div>
                    <div class="dm-checkout-block">
                      <h4><strong>Step #3:</strong> Checkout</h4>
                      ' . $this -> membermouse_card_information() . '
                      ' . $this -> block_submit_button() . '
                    </div>
                    <div class="dm-secure-block">
                      ' . $this -> block_secure() . '
                    </div>

                    <div class="dm-guarantee-block">
                      ' . $this -> block_guarantee() . '
                    </div>

                  </div>';




    $return .= '[/MM_Form]
              </div>';

    return do_shortcode( $return );
  }


  private function membermouse_account_information() {

    $return = get_field( 'membermouse_contact_data' );

    return $return;

  }

  private function membermouse_billing_information() {

    $return = get_field( 'membermouse_billing_address' );

    return $return;

  }

  private function membermouse_card_information() {

    $return = get_field( 'membermouse_card_information' );

    return $return;

  }

  private function block_product_data() {

    $return = "<div class='dm-checkout-product-details'>

                <div class='dm-product-table'>
                    <div class='row dm-table-head'>
                        <div class='col-md-8 dm-product-name'>
                          Product
                        </div>
                        <div class='col-md-4 dm-product-price'>
                          Price
                        </div>
                    </div>
                    <div class='row dm-table-body'>
                        <div class='col-md-8 dm-product-name'>
                          [MM_Form_Data name='productName' useAccessName='false']
                        </div>
                        <div class='col-md-4 dm-product-price'>
                          [MM_Form_Data name='productPriceDescription']
                        </div>
                    </div>
                </div>

              </div>";

    return $return;
  }

  private function block_secure() {

    $secure_lock = get_field( 'dm_checkout_secure_lock' );
    $secure_text = get_field( 'dm_checkout_secure_text' );

    $return = '<div class="col-sm-4">
                    <img src="' . $secure_lock . '" class="img-responsive">
               </div>
               <div class="col-sm-8">
                    ' . $secure_text . '
               </div>';

    return $return;

  }

  private function block_submit_button() {

    $submit_button_text = get_field( 'dm_checkout_submit_button_text' );

    $return = '<div class="mm-paymentbuttons">[MM_Form_Button type=\'all\' label="' . $submit_button_text . '" color=\'orange\']</div>';

    return $return;

  }





  private function block_guarantee() {

    $guarantee_badge = get_field( 'dm_checkout_guarantee_badge' );
    $guarantee_text = get_field( 'dm_checkout_guarantee_text' );

    $return = '<div class="col-sm-8">
                    ' . $guarantee_text . '
               </div>
               <div class="col-sm-4">
                    <img src="' . $guarantee_badge . '" class="img-responsive">
               </div>';

    return $return;

  }

  public function checkout_header() {
    $return = '<div class="site-container dm-checkout-header">
                  <div class="col-sm-6 dm-column-left">

                    <div class="title-area">
                    <h1 class="site-title" itemprop="headline"><a href="http://liberalarts.staging.wpengine.com/">Liberal Arts Colleges</a></h1>
                    <p class="site-description" itemprop="description">the Complete Guide to Liberal Arts Colleges</p>
                    </div>

                  </div>
                  <div class="col-sm-6 dm-column-right">' . get_field( 'dm_checkout_header_right_text' ) . '</div>
                </div>';

    return $return;
  }


  public function checkout_footer() {
    $return = '<footer class="site-container site-footer dm-checkout-footer">
                  <div class="wrap"><br><p>Copyright ©&nbsp;2017 LiberalArtsColleges.com | <a href="/disclosure">Disclosure</a>   |  <a href="/about">About</a>  |  <a href="/recommended-resources">Resources</a>  | <a href="/contact">Contact Us</a> </p>  |  <a href="https://plus.google.com/110342774730564883260" rel="publisher">Google+</a> </div>
                </footer>';

    return $return;
  }


}
