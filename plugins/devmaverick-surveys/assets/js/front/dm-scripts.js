jQuery( document ).ready(function($) {

    dmAddFieldsPlaceholders();


    function dmAddFieldsPlaceholders() {
      // Member's Login page
      $('#log').attr('placeholder', 'Username');
      $('#pwd').attr('placeholder', 'Password');

      // Forgot Password
      $('.mm-forgot-password #email').attr('placeholder', 'Your email address');
    }



});
