// This function triggers the modal tht appears after clicking the submit button
// https://wordpress.org/support/topic/javascript-callbacks-clarification/

function favorites_after_button_submit(favorites){

  if ($( ".simplefavorite-button" ).hasClass( "active" )) {
    $('#dm-add-note-rating-modal').modal('show');
  }
	jQuery('.simplefavorite-button.active').after('<div class="fav_success"><i class="fa fa-info-circle"></i>Contract successfully added to your clipboard.</div>');
	jQuery('.fav_success').fadeIn(1500).delay(5000).fadeOut(1500);
}
