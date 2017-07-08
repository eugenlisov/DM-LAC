jQuery( document ).ready(function($) {

  // Initialize Select 2 on the 'compare schools page'
  $( '.dm-school-1-container select, .dm-school-2-container select' ).select2();

    dmAddFieldsPlaceholders();


    function dmAddFieldsPlaceholders() {
      // Member's Login page
      $('#log').attr('placeholder', 'Username');
      $('#pwd').attr('placeholder', 'Password');

      // Forgot Password
      $('.mm-forgot-password #email').attr('placeholder', 'Your email address');
    }







    $( document ).on( "click", '.trigger-load-college-comparison-block', function() {
      var college_1 = $( '.dm-school-1-container select' ).val();
      var college_2 = $( '.dm-school-2-container select' ).val();

      console.log( college_1 );
      console.log( college_2 );

      loadCollegeComparisonBlock( college_1, college_2 );
    });





    function loadCollegeComparisonBlock( college_1, college_2 ) {

      var data = {
        college_1: college_1,
        college_2: college_2,
      };

      jQuery.post(
              ajaxurl,
                  {
                    'action': 'load_college_comparison_block',
                    'data':   data
                  },
                  function(response){
                    // $( '.dm-generate-quote-btn img' ).hide();
                    console.log(response);

                    var obj = jQuery.parseJSON( response );

                    jQuery( 'body' ).find( ".dm-compare-colleges-block-container" ).html( obj );

                  }
              );
    }




});
