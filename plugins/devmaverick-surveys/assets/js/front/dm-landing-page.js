
jQuery( document ).ready(function($) {

  $( document ).on( "click", ".trigger-scroll-to-pricing-table", function( e ) {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: $( '.desktop-pricing-tables' ).offset().top
    }, 2000);
  });

});
