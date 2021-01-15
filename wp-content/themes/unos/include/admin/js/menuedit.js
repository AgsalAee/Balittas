jQuery(document).ready(function($) {
	"use strict";

	// Init color field
	$('.hoot-color').wpColorPicker();

	// Top Level Only fields
	$('#menu-to-edit').on('sortstop', function( event, ui ) {

		var menuItem = ui.item.eq(0),
			menuItemUpdated = ui.placeholder.eq(0);

		if( menuItemUpdated.hasClass('menu-item-depth-0') )
			menuItem.find('p.hoot_top_level_only').show();
		else
			menuItem.find('p.hoot_top_level_only').hide();

	});

});