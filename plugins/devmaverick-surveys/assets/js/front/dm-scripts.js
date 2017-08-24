jQuery( document ).ready(function($) {

    dmAddFieldsPlaceholders();

    function dmAddFieldsPlaceholders() {
      // Member's Login page
      $('#log').attr('placeholder', 'Username');
      $('#pwd').attr('placeholder', 'Password');

      // Forgot Password
      $('.mm-forgot-password #email').attr('placeholder', 'Your email address');
    }


    delayVasCtaBtn();
    function delayVasCtaBtn() {

      if ( typeof ctaBtnDelay !== 'undefined'  ) {

            delay = ctaBtnDelay * 60 * 1000; // In miliseconds.

            setTimeout(function(){
              $( '.dm-vsl-cta-btn' ).addClass( 'dm-vsl-cta-btn-visible' );
            }, delay);

      }

    }

});
