jQuery( document ).ready(function($) {

  var dmTimestampStart;
  var dmTimestampFinish;

    // Initialize Select 2 on the 'compare schools page'
    $( '.dm-school-1-container select, .dm-school-2-container select' ).select2();

    $( document ).on( "click", '.trigger-load-college-comparison-block', function() {
      var college_1 = $( '.dm-school-1-container select' ).val();
      var college_2 = $( '.dm-school-2-container select' ).val();

      // console.log( college_1 );
      // console.log( college_2 );

      loadCollegeComparisonBlock( college_1, college_2 );
    });



    $( document ).on( "change", '.dm-compare-colleges-select-block select', function() {
      // console.log( 'Change takes place' );
      enableCompareButton();
    });


    function enableCompareButton() {
      var college_1 = $( '.dm-school-1-container select' ).val();
      var college_2 = $( '.dm-school-2-container select' ).val();
      var button = $( '.dm-load-college-comparison-btn' );

      // console.log( 'college_1' );
      // console.log( college_1 );
      // console.log( 'college_2' );
      // console.log( college_2 );

      if ( ( ! college_1 ) || ( ! college_2 ) || ( college_1 == college_2 ) ) {
        button.hide();
      } else {
        button.show();
      }

    }

    // displayLoader();
    function displayLoader() {

      dmTimestampStart = Math.floor( Date.now() );

      var container = $( '.dm-compare-colleges-block-container' );

      var loader = '<div id="loader"></div>';
      var title1 = '<h2>Looking up data...</h2>';
      var title2 = '<h2>Generating Report... </h2>';
      var title3 = '<h2>All set... </h2>';

      container.html( '' );

      setTimeout(function(){
        container.html( loader );
      }, 750);
      setTimeout(function(){
        container.append( title1 );
      }, 1500);
      setTimeout(function(){
        container.find( 'h2' ).remove();
        container.append( title2 );
      }, 2500);
      setTimeout(function(){
        container.find( 'h2' ).remove();
        container.append( title3 );
      }, 4000);

    }

    function removeLoader( obj ) {

      var container = $( '.dm-compare-colleges-block-container' );
      dmTimestampStop = Math.floor(Date.now());

      if ( dmTimestampStop - dmTimestampStart  > 5000 ) {
        container.html( obj );
      } else {
        var customInterval = setInterval(function(){
            dmTimestampStop = Math.floor(Date.now() );

            if ( Number( dmTimestampStop ) - Number( dmTimestampStart )  >= 5000 ) {
              container.html( obj );
              clearInterval( customInterval );
            }
        }, 250);
      }

    }


    function loadCollegeComparisonBlock( college_1, college_2 ) {

      var data = {
        college_1: college_1,
        college_2: college_2,
      };

      displayLoader();

      jQuery.post(
              ajaxurl,
                  {
                    'action': 'load_college_comparison_block',
                    'data':   data
                  },
                  function(response){
                    // $( '.dm-generate-quote-btn img' ).hide();
                    // console.log(response);

                    var obj = jQuery.parseJSON( response );
                    removeLoader( obj );
                  }
              );
    }


});
