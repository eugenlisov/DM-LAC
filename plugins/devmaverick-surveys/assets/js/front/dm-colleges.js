function favorites_after_button_submit(favorites){

  if ($( ".simplefavorite-button" ).hasClass( "active" )) {
    $('#dm-add-note-modal').modal('show');
  }
	// jQuery('.simplefavorite-button.active').after('<div class="fav_success"><i class="fa fa-info-circle"></i>Contract successfully added to your clipboard.</div>');
	// jQuery('.fav_success').fadeIn(1500).delay(5000).fadeOut(1500);
}


jQuery( document ).ready(function($) {




  modalNote();
  function modalNote() {

    // Trigger the single note Save
    $( document ).on( "click", '.trigger-save-note', function() {

      var note = $('#dm-single-note').val();
      var schoolId = $('#dm-single-note').attr('school-id');
      var userId = $('#dm-single-note').attr('user-id');
      var ajaxData = {};

      ajaxData['post_id'] = schoolId;
      ajaxData['user_id'] = userId;
      ajaxData['note'] = note;

      $( this ).parents( '.modal-body' ).html( '<div style="text-align: center;"><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i></div>' );

      ajaxSaveSchoolNote( ajaxData );

    });

  }

  modalRating();
  function modalRating() {

      $( document ).on( "click", '.dm-rating .fa', function() {

        // Add 'filled' to current star and previous siblings.
        $('.dm-rating .fa').removeClass( 'filled' );
        $( this ).addClass( 'filled' ).prevAll().addClass( 'filled' );

        // Do the actual rating stuff
        var rating = $( this ).attr('rating');
        var schoolId = $('#dm-single-note').attr('school-id');
        var userId = $('#dm-single-note').attr('user-id');
        var ajaxData = {};

        ajaxData['post_id'] = schoolId;
        ajaxData['user_id'] = userId;
        ajaxData['rating']  = rating;

        var previousRating = $( '.dm-my-rating h2' ).text();
        if ( previousRating == rating ) {
          alert( 'You clicked on the same rating as before. Try again!' );
        } else {
          $( '.dm-my-rating h2' ).text( rating );
          $( this ).parents( '.dm-modal-editable-rating-conteiner' ).find( '.dm-rating-action' ).slideUp();
          $( this ).parent().html( '<i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i>' );

          ajaxSaveSchoolRating( ajaxData );
        }

      });

    }


  $('.trigger-login-upgrade-modal').click(function() {
    $('#dm-login-upgrade-modal').modal('show');
  });
  $('.trigger-login-upgrade-modal').click(function() {
    $('#dm-add-note-modal').modal('show');
  });
  $('.trigger-add-note-modal').click(function() {
    ajaxGetSchoolNoteModalContent()
    $('#dm-add-note-modal').modal('show');
  });
  $('.trigger-school-rating-modal').click(function() {
    ajaxGetSchoolRatingModalContent();
    $('#dm-school-rating-modal').modal('show');
  });

  //Contact school
  $('.trigger-contact-school-modal').click(function() {
    $('#dm-contact-school-modal').modal('show');
  });


ratingModalActions()
function ratingModalActions() {

  $( document ).on( "click", '.trigger-change-rating', function() {
    // Slide the content into view
    $( this ).parents( '.dm-modal-fixed-rating-conteiner' ).slideUp();
    $( this ).parents( '.modal-body' ).find( '.dm-modal-editable-rating-conteiner' ).slideDown();

    // Change the modal title
    $( this ).parents( '.modal-content' ).find( '.modal-header h2' ).fadeOut(function() {
        $(this).text("Update Your Rating");
      }).fadeIn();
  });

  $( document ).on( "click", '.trigger-cancel-rating-change', function() {
    // Slide the content into view
    $( this ).parents( '.dm-modal-editable-rating-conteiner' ).slideUp();
    $( this ).parents( '.modal-body' ).find( '.dm-modal-fixed-rating-conteiner' ).slideDown();

    // Change the modal title
    $( this ).parents( '.modal-content' ).find( '.modal-header h2' ).fadeOut(function() {
        $(this).text("Your Rating:");
      }).fadeIn();
  });

}



noteModalActions()
function noteModalActions() {

  $( document ).on( "click", '.trigger-edit-note', function() {
    // Slide the content into view
    $( this ).parents( '.dm-modal-existing-note-container' ).slideUp();
    $( this ).parents( '.modal-body' ).find( '.dm-modal-editable-note-container' ).slideDown();

    // Change the modal title
    $( this ).parents( '.modal-content' ).find( '.modal-header h2' ).fadeOut(function() {
        $(this).text("Update Your Note");
      }).fadeIn();
  });

  $( document ).on( "click", '.trigger-cancel-note-edit', function() {
    // Slide the content into view
    $( this ).parents( '.dm-modal-editable-note-container' ).slideUp();
    $( this ).parents( '.modal-body' ).find( '.dm-modal-existing-note-container' ).slideDown();

    // Change the modal title
    $( this ).parents( '.modal-content' ).find( '.modal-header h2' ).fadeOut(function() {
        $(this).text("Your Note:");
      }).fadeIn();
  });

}







  function ajaxSaveSchoolNote( ajaxData ) {
    $.post(
            ajaxurl,
                {
                  'action': 'dm_save_school_note',
                  'data':   ajaxData
                },
                function(response){
                  message = JSON.parse( response );
                  $( '#dm-add-note-modal .modal-body, #dm-add-note-modal .modal-header h2' ).remove();
                  $( '#dm-add-note-modal .modal-header' ).append( message );
                  setTimeout(function(){
                    $('#dm-add-note-modal').modal('hide');

                    // After another second empty the modal completely.
                    setTimeout(function(){
                        $('#dm-add-note-modal .modal-content').html( '' );
                    }, 1000);

                  }, 2500);
                }
            );
  }

  function ajaxSaveSchoolRating( ajaxData ) {
    $.post(
            ajaxurl,
                {
                  'action': 'dm_save_school_rating',
                  'data':   ajaxData
                },
                function(response){
                  message = JSON.parse( response );
                  $( '#dm-school-rating-modal .modal-body, #dm-school-rating-modal .modal-header h2' ).remove();
                  $( '#dm-school-rating-modal .modal-header' ).append( message );
                  setTimeout(function(){
                    $('#dm-school-rating-modal').modal('hide');

                      // After another second empty the modal completely.
                      setTimeout(function(){
                          $('#dm-school-rating-modal .modal-content').html( '' );
                      }, 1000);

                  }, 2500);
                }
            );
  }

  function ajaxGetSchoolRatingModalContent() {
    var schoolID = $( '.dm-college-top-content' ).attr( 'school-id' );
    $.post(
            ajaxurl,
                {
                  'action': 'dm_get_school_rating_modal',
                  'data':   schoolID
                },
                function(response){
                  message = JSON.parse( response );
                  $('#dm-school-rating-modal .modal-content').html( message );
                }
            );
  }

  function ajaxGetSchoolNoteModalContent() {
    var schoolID = $( '.dm-college-top-content' ).attr( 'school-id' );
    $.post(
            ajaxurl,
                {
                  'action': 'dm_get_school_note_modal',
                  'data':   schoolID
                },
                function(response){
                  message = JSON.parse( response );
                  $('#dm-add-note-modal .modal-content').html( message );
                }
            );
  }
});
