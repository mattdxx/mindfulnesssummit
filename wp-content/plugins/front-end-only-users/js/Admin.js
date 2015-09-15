/* Used to show and hide the admin tabs for FEUP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

jQuery(document).ready(function() {	
	jQuery('.ewd-feup-one-click-install-div-load').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').addClass('ewd-feup-grey-out');
		jQuery('#ewd-feup-one-click-blur').width(jQuery('#ewd-feup-one-click-blur').width() + 180);
	});

	jQuery('#ewd-feup-one-click-blur').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').removeClass('ewd-feup-grey-out');
	});

});

/* This code is required to make changing the field order a drag-and-drop affair */
jQuery(document).ready(function() {	
	jQuery('.fields-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_field_order';
			jQuery.post(ajaxurl, order, function(response) {});
		}
	});

	/*jQuery('.levels-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_levels_order';
			alert(order);
			jQuery.post(ajaxurl, order, function(response) {alert(response);});
		}
	});*/
});