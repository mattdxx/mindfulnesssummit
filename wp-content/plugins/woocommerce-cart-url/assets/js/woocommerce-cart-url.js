jQuery( document ).ready( function( $ ) {

	// Add a line item
	$('#wcu-products button.add_cart_url_item').on( 'click', function(){

		var add_item_ids = $('#add_item_id').val().split( ',' );

		if ( add_item_ids ) {

			count = add_item_ids.length;

			$('table.wcu_products').block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 }});

			$.each( add_item_ids, function( index, value ) {

				var data = {
					action: 		'wcu_add_product',
					item_to_add: 	value,
					post_id:		wcu.post_id,
					security: 		wcu.wcu_item_nonce
				};

				$.post( wcu.ajax_url, data, function( response ) {

					$('table.wcu_products tbody#order_items_list').append( response );

					if ( ! --count ) {
						$('#add_item_id').css('border-color', '').val('');
					    $('table.wcu_products').unblock();
					}

					$('#order_items_list tr.new_row').trigger('init_row').removeClass('new_row');
				});

			});

		} else {
			$('#add_item_id').css('border-color', 'red');
		}

		$( '#wcu-products .wc-product-search' ).val( null ).trigger( 'change' );
		return false;
	});


	// Edit quantity
	$( '#wcu-products' ).on( 'click', '.edit_cart_item', function( event ) {

		$( '.edit[data-item-id=' + $(this).attr('data-item-id') + ']' ).css( 'display', 'block' );
		$( '.view[data-item-id=' + $(this).attr('data-item-id') + ']' ).css( 'display', 'none' );

	});


	// Check/un-check all
	$('#wcu-products').on( 'click', 'input.check-column', function() {
		if ( $(this).is(':checked') )
			$('#wcu-products').find('.check-column input').attr('checked', 'checked');
		else
			$('#wcu-products').find('.check-column input').removeAttr('checked');
	} );

	// Bulk edit
	$('#wcu-products').on( 'click', '.do_bulk_action', function() {

		var action = $(this).closest('.bulk_actions').find('select').val();
		var selected_rows = $('#order_items_list').find('.check-column input:checked');
		var item_ids = [];

		$(selected_rows).each( function() {

			var $item = $(this).closest('tr.item, tr.fee');

			item_ids.push( $item.attr( 'data-order_item_id' ) );

		} );

		if ( item_ids.length == 0 ) {
			alert( 'Please select at least one product.' );
			return;
		}

		if ( action == 'delete' ) {

			var answer = confirm( 'Are you sure you want to remove the selected items?' );

			if ( answer ) {

				$('table.wcu_products').block({
					message: null,
					overlayCSS: {
						background: '#fff url(' + wcu.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center',
						opacity: 0.6
					}
				});

				var data = {
					remove_ids:		 	item_ids,
					post_id:			wcu.post_id,
					action: 			'wcu_remove_product',
					security: 			wcu.wcu_item_nonce
				};

				$.ajax( {
					url: wcu.ajax_url,
					data: data,
					type: 'POST',
					success: function( response ) {
						$(selected_rows).each( function() {
							$(this).closest('tr.item, tr.fee').remove();
						} );
						$('table.wcu_products').unblock();
					}
				} );

			}

		}

		return false;
	} );


});