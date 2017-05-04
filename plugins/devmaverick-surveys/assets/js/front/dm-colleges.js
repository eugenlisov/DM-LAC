jQuery( document ).ready(function($) {


  // Show and hide the baloons
  showHideBaloons()
  function showHideBaloons() {
        // Show notes
          $(".dm-my-note, .dm-hover-content.notes").mouseenter(function(){
              windowWidth = jQuery( window ).width();
              if (windowWidth >= 768) {
                clearTimeout($('.dm-hover-content.notes').data('timeoutId'));
                $(".dm-hover-content.notes").fadeIn("100");
              }

          }).mouseleave(function(){
            if (windowWidth >= 768) {
              var someElement = $('.dm-hover-content.notes'),
                  timeoutId = setTimeout(function(){
                      $(".dm-hover-content.notes").fadeOut("100");
                  }, 1050);
              //set the timeoutId, allowing us to clear this trigger if the mouse comes back over
              someElement.data('timeoutId', timeoutId);
            }
          });

        // Show colleges
                 $(".dm-my-colleges, .dm-hover-content.colleges").mouseenter(function(){
                      windowWidth = jQuery( window ).width();
                      if (windowWidth >= 768) {
                        clearTimeout($('.dm-hover-content.colleges').data('timeoutId'));
                        $(".dm-hover-content.colleges").fadeIn("100");
                      }

                  }).mouseleave(function(){
                    if (windowWidth >= 768) {
                      var someElement = $('.dm-hover-content.colleges'),
                          timeoutId = setTimeout(function(){
                              $(".dm-hover-content.colleges").fadeOut("100");
                          }, 1050);
                      //set the timeoutId, allowing us to clear this trigger if the mouse comes back over
                      someElement.data('timeoutId', timeoutId);
                    }
                  });
    }

// END

  // Trigger the single note Save
  $('#dm-single-note-button').click(function() {
    var note = $('#dm-single-note').val();
    var schoolId = $('#dm-single-note').attr('school-id');
    var userId = $('#dm-single-note').attr('user-id');
    var ajaxData = {};

    ajaxData['post_id'] = schoolId;
    ajaxData['user_id'] = userId;
    ajaxData['note'] = note;

    ajaxSaveSchoolNote( ajaxData );

  });

  $('.dm-rating .fa').click(function() {
    var rating = $( this ).attr('rating');
    var schoolId = $('#dm-single-note').attr('school-id');
    var userId = $('#dm-single-note').attr('user-id');
    var ajaxData = {};

    ajaxData['post_id'] = schoolId;
    ajaxData['user_id'] = userId;
    ajaxData['rating'] = rating;

    ajaxSaveSchoolRating( ajaxData );

  });


  $('.trigger-login-upgrade-modal').click(function() {
    $('#dm-login-upgrade-modal').modal('show');
  });
  $('.trigger-login-upgrade-modal').click(function() {
    $('#dm-add-note-rating-modal').modal('show');
  });
  $('.trigger-add-note-modal').click(function() {
    $('#dm-add-note-rating-modal').modal('show');
  });

  //Contact school
  $('.trigger-contact-school-modal').click(function() {
    $('#dm-contact-school-modal').modal('show');
  });








  function ajaxSaveSchoolNote( ajaxData ) {
    $.post(
            ajaxurl,
                {
                  'action': 'dm_save_school_note',
                  'data':   ajaxData
                },
                function(response){
                  console.log(response);
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
                  console.log(response);
                }
            );
  }
});
