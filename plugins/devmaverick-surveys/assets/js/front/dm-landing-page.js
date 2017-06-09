$( document ).ready(function($) {

  $( "a:contains('Get Premium Now')" ).addClass( ".trigger-show-checkout-form" );

  $( document ).on( "click", ".trigger-show-checkout-form", function( e ) {
    e.preventDefault();
    $( '.dm-checkout-container' ).slideDown();

    $('html, body').animate({
        scrollTop: $( '.dm-checkout-container' ).offset().top
    }, 2000);

  });

});
