jQuery( document ).ready(function($) {

  // Initialize Select 2

  $( '.dm-question select' ).select2();


  $( document ).on( "change", '.dm-question select', function() {
    var targetedQuestion = $( this ).val();
    var row = $( this ).parents( '.dm-setup-comparator-question-row' ).attr( 'question-id' );
    console.log( $( this ).val() );

    loadQuestionResponses( targetedQuestion, row );
  });




  $( document ).on( "click", '.trigger-save-comparator-setup', function(e) {
    e.preventDefault();

    var formData = $( '.dm-setup-comparator-form' ).serialize();
    console.log( formData );

    saveComparatorSetup( formData );
  });









  function loadQuestionResponses( targetedQuestion, row ) {

    var data = {
      targeted_question: targetedQuestion,
      row: row,
    };

    jQuery.post(
            ajaxurl,
                {
                  'action': 'load_question_responses',
                  'data':   data
                },
                function(response){
                  // $( '.dm-generate-quote-btn img' ).hide();
                  console.log(response);

                  var obj = jQuery.parseJSON( response );

                  jQuery( 'body' ).find( ".dm-setup-comparator-question-row[question-id='" + row + "']" ).find( '.dm-response' ).html( obj );

                }
            );
  }

  function saveComparatorSetup( formData ) {
    jQuery.post(
            ajaxurl,
                {
                  'action': 'save_comparator_setup',
                  'data':   formData
                },
                function(response){
                  // $( '.dm-generate-quote-btn img' ).hide();
                  console.log(response);

                  // var obj = jQuery.parseJSON( response );

                  // jQuery( 'body' ).find( ".dm-setup-comparator-question-row[question-id='" + row + "']" ).find( '.dm-response' ).html( obj );

                }
            );
  }


});
