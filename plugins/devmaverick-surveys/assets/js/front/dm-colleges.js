function favorites_after_button_submit(favorites){
  //https://wordpress.org/support/topic/javascript-callbacks-clarification/
  var schoolName = $( 'article h1.entry-title' ).text();
  var closeBtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';

  if ($( ".simplefavorite-button" ).hasClass( "active" )) {
    ajaxGetRatingAndNoteButtons();
    var message = '<div class="modal-header">' + closeBtn +
                  ' <div style="text-align: center;">' +
									' <h3>We\'ve added ' + schoolName + ' to your colleges list!</h3>' +
									' <i class="fa fa-check fa-4x" aria-hidden="true" style="color: #1ab394; "></i>' +
								' </div>' +
                '</div>';
    $('#dm-add-note-modal .modal-content').html( message );
    $('#dm-add-note-modal').modal('show');
  } else {
    var messageRemoved = '<div class="modal-header">' + closeBtn +
                  ' <div style="text-align: center;">' +
                  ' <h3>We\'ve removed ' + schoolName + ' from your colleges list!</h3>' +
                  ' <i class="fa fa-frown-o fa-4x" aria-hidden="true" style="color: #ff5722; "></i>' +
                ' </div>' +
                '</div>';
    $('#dm-add-note-modal .modal-content').html( messageRemoved );
    $('#dm-add-note-modal').modal('show');

    // Remove ratings and stars Buttons
    $( '.dm-college-top-content' ).find( '.dm-my-rating' ).remove();
    $( '.dm-college-top-content' ).find( '.dm-my-note' ).remove();

  }
}



function ajaxGetRatingAndNoteButtons() {
  var schoolID = $( '.dm-college-top-content' ).attr( 'school-id' );
  $.post(
          ajaxurl,
              {
                'action': 'dm_get_rating_and_note_buttons',
                'data':   schoolID
              },
              function(response){

                // console.log( response );
                buttons = JSON.parse( response );
                $( '.dm-college-top-content' ).append( buttons );
              }
          );
}


jQuery( document ).ready(function($) {




  modalNote();
  function modalNote() {

    // Make the submit button visible only after there's some change in the textfield - Deocamdata mai bine nu
    // $( document ).on( "keyup", '.dm-single-note', function() {
    //   $( this ).parents( '.modal-body' ).find( '.trigger-save-note' ).slideDown();
    // });

    // Trigger the single note Save
    $( document ).on( "click", '.trigger-save-note', function() {

      var note = $( this ).parents( '.modal-content' ).find('.dm-single-note').val();
      var schoolId = $( '.dm-college-top-content' ).attr('school-id');
      var userId = $( '.dm-college-top-content' ).attr('user-id');
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
        var schoolId = $( '.dm-college-top-content' ).attr('school-id');
        var userId = $( '.dm-college-top-content' ).attr('user-id');
        var ajaxData = {};

        ajaxData['post_id'] = schoolId;
        ajaxData['user_id'] = userId;
        ajaxData['rating']  = rating;
        console.log( ajaxData );

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
  $( document ).on( "click", '.trigger-add-note-modal', function() {
    ajaxGetSchoolNoteModalContent()
    $('#dm-add-note-modal').modal('show');
  });
  $( document ).on( "click", '.trigger-school-rating-modal', function() {
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


// My colleges page
myCollegesSaveNotes();
function myCollegesSaveNotes() {

  // Trigger the single note Save
  $( document ).on( "click", '.trigger-my-colleges-save-note', function() {



    var note = $( this ).parents( '.dm-my-college-tabs .tab-content' ).find('.dm-single-note').val();
    if ( !note ) {
      alert( 'You haven\'t written anything. Please add a note and then try again' );
    }

    var schoolId  = $( this ).parents('.dm-my-colege-item').attr('school-id');
    var userId    = $( this ).parents('.dm-my-coleges-list').attr('user-id');
    var ajaxData = {};

    ajaxData['post_id'] = schoolId;
    ajaxData['user_id'] = userId;
    ajaxData['note'] = note;

    $( this ).parents( '.tab-pane' ).html( '<div class="dm-my-colleges-loader"><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i></div>' );

    ajaxMyCollegesSaveSchoolNote( ajaxData );

  });

}
myCollegesNotesActions()
function myCollegesNotesActions() {

  $( document ).on( "click", '.trigger-my-colleges-edit-note', function() {
    // Slide the content into view
    $( this ).parents( '.dm-my-colleges-existing-note-container' ).slideUp();
    $( this ).parents( '.dm-my-college-tabs' ).find( '.dm-my-colleges-editable-note-container' ).slideDown();

    // Change the modal title
    $( this ).parents( '.modal-content' ).find( '.modal-header h2' ).fadeOut(function() {
        $(this).text("Update Your Note");
      }).fadeIn();
  });

  $( document ).on( "click", '.trigger-my-colleges-cancel-note-edit', function() {
    // Slide the content into view
    $( this ).parents( '.dm-my-colleges-editable-note-container' ).slideUp();
    $( this ).parents( '.dm-my-college-tabs' ).find( '.dm-my-colleges-existing-note-container' ).slideDown();

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
  function ajaxMyCollegesSaveSchoolNote( ajaxData ) {
    $.post(
            ajaxurl,
                {
                  'action': 'dm_my_colleges_save_school_note',
                  'data':   ajaxData
                },
                function(response){
                  // console.log( response );
                  message = JSON.parse( response );
                  // console.log( message );

                  $( '.dm-my-colege-item[school-id="' + ajaxData.post_id + '"]' ).find( '.dm-tab-notes' ).html( message );

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
