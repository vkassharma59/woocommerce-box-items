jQuery( document ).ready( function( $ ) {

	show_and_hide_panels();

	// Product type specific options.
	$( 'select#product-type' ).change( function() {

		// Get value.
		var select_val = $( this ).val();
		show_and_hide_panels();
	});

	$( 'input#_box' ).change( function() {
		// this is test
		show_and_hide_panels();
	});

	function show_and_hide_panels() {

		var is_box = $( 'input#_box:checked' ).length;
		console.log(is_box);

		if( is_box ) {
			$( '.show_if_box_type' ).show();
		} else {
			$( '.show_if_box_type' ).hide();
		}
	}
});
