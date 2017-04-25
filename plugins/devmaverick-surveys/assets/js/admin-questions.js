jQuery( document ).ready(function($) {

    //Save the narratives
    $( '.dm-questions-table textarea' ).change(function() {

      var questionId  = $( this ).parents('tr').attr('question-id');
      var contentType = $( this ).attr('content-type');
      var currentText = $( this ).val();

      var textareaData = new Object();

      textareaData["question-id"]  = questionId;
      textareaData["content-type"] = contentType;
      textareaData["current-text"] = currentText;

      ajaxSaveTextArea( textareaData );

      console.log(textareaData);

      // alert( contentType );
      // alert( currentText );


    });

    function ajaxSaveTextArea( textareaData ) {
      // console.log(textareaData);
      jQuery.post(
              ajaxurl,
                  {
                    'action': 'dm_save_question_narratives',
                    'data':   textareaData
                  },
                  function(response){
                    console.log(response);
                  }
              );
  }

});
