/* This code is required to make changing the catalogue order a drag-and-drop affair */
jQuery(document).ready(function() {
		
		jQuery('.level-list').sortable({
				items: '.list-item',
				opacity: 0.6,
				cursor: 'move',
				axis: 'y',
				update: function() {
						var order = jQuery(this).sortable('serialize') + '&action=levels_update_order';
						jQuery.post(ajaxurl, order, function(response) {});
				}
		});
});