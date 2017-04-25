$( document ).ready(function($) {

  $( ".landing-benefits-sidebar button" ).click(function() {
    $('.dm-checkout-container').slideDown();
    $( this ).hide();
  });

});
