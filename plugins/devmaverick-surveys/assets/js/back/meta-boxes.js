jQuery( document ).ready(function($) {


    // Trigger the ajax call on dropdown change
    $( ".dm-open-ended-question input[type='checkbox']" ).change(function() {
      // if ($( this ).is(':checked')) {

        var allQuestionBlocks = $( ".dm-open-ended-question" );
        var responseData = {};
        var questions = {};

        allQuestionBlocks.each(function( index ) {
          var currentQuestionId  = $(this).attr('question-number');
          var selectedCheckboxes = $(this).find( "input:checked" );

          if (selectedCheckboxes.length > 0) {
            questions[currentQuestionId] = {};
          }

          selectedCheckboxes.each(function( indexCheckboxes ) {
            var currentResponseId   = $(this).parents('tr').attr('response_id');
            var currentRespondentId = $(this).parents('tr').attr('respondent_id');

            responseData['question-id']   = currentQuestionId;
            responseData['response-id']   = currentResponseId;
            responseData['respondent-id'] = currentRespondentId;

            questions[currentQuestionId][currentResponseId] = responseData;

          });

        });
        var post_id = $('#dm-post-id').attr('post-id');
        var ajaxData = {};

        ajaxData['post_id'] = post_id;
        ajaxData['questions'] = questions;

        ajaxSaveOpenEndedQuestion( ajaxData );
      // }
    });







    $( "body" ).on( "click", ".feature-it", function() {

      var loader = '<img class="dm-loader" src="http://liberalarts.staging.wpengine.com/wp-admin/images/spinner.gif">';


      var post_id         = $('#dm-post-id').attr('post-id');
      var response_id     = $( this ).parents('.dm-data-holder').attr('response-id');
      var respondent_id   = $( this ).parents('.dm-data-holder').attr('respondent-id');
      var graduation_year = $( this ).parents('.dm-data-holder').attr('graduation-year');
      var q_all_number    = $( this ).parents('.dm-data-holder').attr('q_all_number');
      var response_text    = $( this ).parent().siblings('.dm-response-text').text();

      $( this ).replaceWith(loader);


      var ajaxData = {};

      ajaxData['post_id']         = post_id;
      ajaxData['response_id']     = response_id;
      ajaxData['respondent_id']   = respondent_id;
      ajaxData['graduation_year'] = graduation_year;
      ajaxData['q_all_number']    = q_all_number;
      ajaxData['response_text']   = response_text;

      // console.log(ajaxData);

      ajaxSaveOpenEndedQuestion( ajaxData );

    });


    $( "body" ).on( "click", ".remove-it", function() {

      var loader = '<img class="dm-loader" src="http://liberalarts.staging.wpengine.com/wp-admin/images/spinner.gif">';
 
      var respondent_id     = $( this ).parents('tr').attr('respondent-id');
      var question_id     = $( this ).parents('tr').attr('q_all_number');
      var post_id         = $('#dm-post-id').attr('post-id');
      $( this ).replaceWith(loader);

      var ajaxData = {};

      ajaxData['post_id']         = post_id;
      ajaxData['respondent_id']     = respondent_id;
      ajaxData['question_id']     = question_id;

      ajaxRemoveOpenEndedQuestion( ajaxData );

    });








  function ajaxSaveOpenEndedQuestion( ajaxData ) {
  // console.log( ajaxData );
  // $( '.dm-generate-quote-btn img' ).show();
    jQuery.post(
            ajaxurl,
                {
                  'action': 'dm_save_open_ended_questions',
                  'data':   ajaxData
                },
                function(response){
                  // $( '.dm-generate-quote-btn img' ).hide();
                  console.log(response);

                  var obj = jQuery.parseJSON( response );


                  $('.dm-existing-open-ended-questions').html( obj );
                  $('.dm-loader').replaceWith('<span>Added!</span>')
                }
            );
}

  function ajaxRemoveOpenEndedQuestion( ajaxData ) {
  // console.log( ajaxData );
  // $( '.dm-generate-quote-btn img' ).show();
    jQuery.post(
            ajaxurl,
                {
                  'action': 'dm_remove_open_ended_questions',
                  'data':   ajaxData
                },
                function(response){
                  // $( '.dm-generate-quote-btn img' ).hide();
                  console.log(response);

                  var obj = jQuery.parseJSON( response );


                  $('.dm-existing-open-ended-questions').html( obj );
                  $('.dm-loader').replaceWith('<span>Added!</span>')
                }
            );
}


});
