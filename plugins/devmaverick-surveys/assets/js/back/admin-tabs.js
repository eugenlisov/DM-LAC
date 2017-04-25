jQuery( document ).ready(function($) {

    //Save the narratives
    $( '.dm-admin-tab textarea' ).change(function() {

      var tabId       = $( this ).parents('.dm-admin-tab').attr('tab-id');
      var contentType = $( this ).attr('content-type');
      var currentText = $( this ).val();

      var textareaData = new Object();

      textareaData["tab-id"]       = tabId;
      textareaData["content-type"] = contentType;
      textareaData["current-text"] = currentText;

      ajaxSaveTextArea( textareaData );

      // alert( contentType );
      // alert( currentText );


    });

    function ajaxSaveTextArea( textareaData ) {
      console.log(textareaData);
      jQuery.post(
              ajaxurl,
                  {
                    'action': 'dm_save_tab_narratives',
                    'data':   textareaData
                  },
                  function(response){
                    // $( '.dm-generate-quote-btn img' ).hide();
                    console.log(response);
                    // jQuery( parentBlock ).find( ".dm-product-add-to-quote" ).addClass( "dm-added-to-cart" ).removeClass( "dm-add-to-quote").html( 'Added to cart <i class="fa fa-check" aria-hidden="true"></i>');
                    // if (response.trim() == '"inserted"') {
                    //   // alert('Am inserat cu success');
                    //   //  thirdStepURL is defined in cart-customers.php
                    //   window.location.href = thirdStepURL;
                    // }
                  }
              );
  }





    // Trigger the ajax call on checkbox change
    $( '.dm-admin-tab input[type="checkbox"]' ).change(function() {

      var currentQuestionId = $( this ).val();
      var currentTabId = $( this ).parents('.dm-admin-tab').attr('tab-id');
      var currentAction;

      if ($( this ).is(":checked")) {
        currentAction = 'add';
      } else {
        currentAction = 'remove';
      }

      var tabData = new Object();

      tabData["action"]      = currentAction;
      tabData["tab_id"]      = currentTabId;
      tabData["question_id"]  = currentQuestionId;

      ajaxSaveTabQuestions( tabData );

    });





  function ajaxSaveTabQuestions( tabData ) {
  // console.log(schoolData);
  // $( '.dm-generate-quote-btn img' ).show();
    jQuery.post(
            ajaxurl,
                {
                  'action': 'dm_save_tab_data',
                  'data':   tabData
                },
                function(response){
                  $( '.dm-generate-quote-btn img' ).hide();
                  console.log(response);
                  // jQuery( parentBlock ).find( ".dm-product-add-to-quote" ).addClass( "dm-added-to-cart" ).removeClass( "dm-add-to-quote").html( 'Added to cart <i class="fa fa-check" aria-hidden="true"></i>');
                  if (response.trim() == '"inserted"') {
                    // alert('Am inserat cu success');
                    //  thirdStepURL is defined in cart-customers.php
                    window.location.href = thirdStepURL;
                  }
                }
            );
}


});
