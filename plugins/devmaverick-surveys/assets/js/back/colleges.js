jQuery( document ).ready(function($) {


    // Trigger the IPED ajax call on dropdown change
    $( "table .trigger-select-school-iped select" ).change(function() {

      var currentSchoolId = $( this ).parents('tr').attr('college-id');
      var selectedIPED = $( this ).val();

      var schoolData = new Object();

      schoolData["school_id"]      = currentSchoolId;
      schoolData["selected_iped"]  = selectedIPED;

      ajaxAssociateIpedToSchool( schoolData );

    });

    // Trigger the School Contact ajax call on dropdown change
    $( "table .trigger-select-school-contact select" ).change(function() {

      var currentSchoolId = $( this ).parents('tr').attr('college-id');
      var selectedHighrise_id = $( this ).val();

      var schoolData = new Object();

      schoolData["school_id"]      = currentSchoolId;
      schoolData["selected_highrise_id"]  = selectedHighrise_id;

      // console.log(schoolData);

      ajaxAssociateHighriseIdToSchool( schoolData );

    });


    // The suggestion and remove what's already selected buttons.

    $( "body" ).on( "click", ".trigger-remove-iped", function() {
      $( this ).parents('tr').find('.recalculate').html('');
      $( this ).parents('tr').find('.select-school-iped select option:first').prop('selected', true).change();
      $( this ).parents('td').html('empty for now. you can reload the page');

    });
    $( "body" ).on( "click", ".trigger-remove-highrise-id", function() {
      $( this ).parents('tr').find('.select-school-contact select option:first').prop('selected', true).change();

    });
    $( "body" ).on( "click", ".trigger-iped-suggestion", function() {
      var currentIPED = $( this ).attr('iped');
      $( this ).parents('tr').find('.select-school-iped select option[iped="' + currentIPED + '"]').prop('selected', true).change();
      $( this ).parents('td').html('<span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>');

      $( "input[value='Hot Fuzz']" )
    });

    $( "body" ).on( "click", ".trigger-contact-data-suggestion", function() {
      var currentHighriseId = $( this ).attr('highrise-id');
      $( this ).parents('tr').find('.select-school-contact select option[highrise-id="' + currentHighriseId + '"]').prop('selected', true).change();
      $( this ).parents('td').html('<span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>');

      $( "input[value='Hot Fuzz']" )
    });


    $( "body" ).on( "click", ".recalculate-averages", function() {

      // var selectedIPED = $( this ).attr('iped');
      // alert('recalculate - not working yet');
      //

      var loader = '<img src="http://liberalarts.staging.wpengine.com/wp-admin/images/spinner.gif">';
      $( '.dm-recalculate-column' ).html(loader);

      $.post(
              ajaxurl,
                  {
                    'action': 'dm_recalculate_averages',
                    'data':   ''
                  },
                  function(response){
                    console.log(response);
                    var success_string = '<span class="success-recalculating-average"> <i class="fa fa-check" aria-hidden="true"></i> Success</span>';
                    $( '.dm-recalculate-column' ).html(success_string);

                  }
              );


    });





  function ajaxAssociateIpedToSchool( schoolData ) {
    jQuery.post(
            ajaxurl,
                {
                  'action': 'dm_associate_iped_to_school',
                  'data':   schoolData
                },
                function(response){
                  console.log(response);

                }
            );
}

function ajaxAssociateHighriseIdToSchool( schoolData ) {
  jQuery.post(
          ajaxurl,
              {
                'action': 'dm_associate_highhrise_id_to_school',
                'data':   schoolData
              },
              function(response){
                console.log(response);

              }
          );
}



});
