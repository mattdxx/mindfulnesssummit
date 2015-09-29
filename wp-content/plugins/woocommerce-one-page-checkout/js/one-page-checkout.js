/*
 * One Page Checkout JS: add/remove items from a One Page Checkout page via Ajax
 */
jQuery(document).ready(function($){

	var response_messages = '';
	var timeout;
	var delay = 1000;

	/**
	 * Review Order Template Item Management (Removal & Quantity Adjustment)
	 */

	// Quantity buttons
	$( '.checkout' ).on( 'change input', '#order_review .opc_cart_item div.quantity input.qty', function(e) {

		var input = $(this);

		clearTimeout(timeout);

		timeout = setTimeout(function() {

			var data = {
				quantity:    input.val(),
				add_to_cart: parseInt( input.closest( '.opc_cart_item' ).data( 'add_to_cart' ) ),
				update_key:  input.closest( '.opc_cart_item' ).data( 'update_key' ),
				nonce:       wcopc.wcopc_nonce,
			}

			if ( data['quantity'] == 0 ) {
				data['action'] = 'pp_remove_from_cart';
			} else {
				data['action'] = 'pp_update_add_in_cart';
			}

			input.ajax_add_remove_product( data, e );

		}, delay );

		e.preventDefault();

	} );

	// Remove buttons
	$( '.checkout' ).on( 'click', '#order_review .opc_cart_item a.remove', function(e) {

		var data = {
			action:      'pp_remove_from_cart',
			add_to_cart: parseInt( $(this).closest( '.opc_cart_item' ).data( 'add_to_cart' ) ),
			update_key:  $(this).closest( '.opc_cart_item' ).data( 'update_key' ),
			nonce:       wcopc.wcopc_nonce,
		}

		$(this).ajax_add_remove_product( data, e );

		e.preventDefault();

	} );

	/**
	 * Single Product Template
	 */

	/* Add/remove products with button element or a tags */
	$( '#opc-product-selection button.single_add_to_cart_button, .wcopc-product-single button.single_add_to_cart_button' ).on( 'click', function(e) {

		var is_variable    = $(this).closest( '.variations_form' ).find( 'input[name="variation_id"]' ).length === 1 ? true : false,
			add_to_cart_id = $(this).closest( '.cart' ).find( 'input[name="add-to-cart"]' ).val(),
			has_quantity   = $(this).closest( '.cart' ).find( 'input[name="quantity"]' ).length === 1 ? true : false;

		var data = {
			action:       'pp_add_to_cart',
			add_to_cart:  parseInt( add_to_cart_id ),
			nonce:        wcopc.wcopc_nonce,
			input_data:   $(this).closest( '.product-quantity, .wcopc-product-single form' ).find( 'input[name!="variation_id"][name!="product_id"][name!="add-to-cart"][name!="quantity"], select, textarea' ).serialize(),
		}

		if ( is_variable ) {
			data.variation_id = parseInt( $(this).closest( '.variations_form' ).find( 'input[name="variation_id"]' ).val() );
		}

		// The quantity input field might be missing if a product is sold individually or has only 1 unit of stock remaining
		if ( has_quantity ) {
			data.quantity = parseInt( $(this).closest( '.cart' ).find( 'input[name="quantity"]' ).val() );
		}

		$(this).ajax_add_remove_product( data, e );

		e.preventDefault();

	} );

	/**
	 * Other Templates
	 */

	/* Add/remove products with number input type */
	$( '#opc-product-selection input[type="number"][data-add_to_cart]' ).on( 'change input', function(e) {

		var input = $(this);

		clearTimeout(timeout);

		timeout = setTimeout(function() {

			var data = {
				quantity:    input.val(),
				add_to_cart: parseInt( input.data( 'add_to_cart' ) ),
				input_data:  input.closest( '.product-quantity' ).find( 'input[name!="product_id"], select, textarea' ).serialize(),
				nonce:       wcopc.wcopc_nonce,
			}

			if ( data['quantity'] == 0 ) {
				data['action'] = 'pp_remove_from_cart';
			} else {
				data['action'] = 'pp_update_add_in_cart';
			}

			input.ajax_add_remove_product( data, e );

		}, delay );

		e.preventDefault();

	} );

	/* Add/remove products with radio or checkobox inputs */
	$( '#opc-product-selection input[type="radio"][data-add_to_cart], #opc-product-selection input[type="checkbox"][data-add_to_cart]' ).on( 'change', function(e) {

		var input = $(this);

		clearTimeout(timeout);

		timeout = setTimeout(function() {

			var data = {
				add_to_cart: parseInt( input.data( 'add_to_cart' ) ),
				nonce:       wcopc.wcopc_nonce
			}

			if ( input.is( ':checked' ) ) {

				if ( input.prop( 'type' ) == 'radio' ) {

					data.empty_cart = 'true';
					$( 'input[data-add_to_cart]' ).prop( 'checked', false );
					input.prop( 'checked', true );
					$( '.selected' ).removeClass( 'selected' );
				}

				data.action = 'pp_add_to_cart';
				input.parents( '.product-item' ).addClass( 'selected' );

			} else {

				data.action = 'pp_remove_from_cart';
				input.parents( '.product-item' ).removeClass( 'selected' );

			}

			input.ajax_add_remove_product( data, e );

		}, delay );

	} );

	/* Add/remove products with button element or a tags */
	$( '#opc-product-selection a[data-add_to_cart], #opc-product-selection button[data-add_to_cart]' ).on( 'click', function(e) {

		var data = {
			add_to_cart: parseInt( $(this).data( 'add_to_cart' ) ),
			nonce:       wcopc.wcopc_nonce,
			input_data:  $(this).closest( '.product-quantity' ).find( 'input[name!="product_id"], select, textarea' ).serialize(),
		}

		// Toggle button on or off
		if ( ! $(this).parents( '.product-item' ).hasClass( 'selected' ) ) {
			data.action = 'pp_add_to_cart';
			$(this).parents( '.product-item' ).addClass( 'selected' );
		} else {
			data.action = 'pp_remove_from_cart';
			$(this).parents( '.product-item' ).removeClass( 'selected' );
		}

		$(this).ajax_add_remove_product( data, e );
	} );

	/* Add products from any Easy Pricing Table template */
	$( '#opc-product-selection a.ptp-button, #opc-product-selection a.ptp-fancy-button, #opc-product-selection a.btn.sign-up, #opc-product-selection .ptp-stylish-pricing_button a, #opc-product-selection .ptp-design4-col > a' ).on( 'click',function(e) {

		var productParams = getUrlsParams($(this)[0].search.substring(1));

		if( typeof productParams['variation_id'] == "undefined" ){
			productParams['variation_id'] = null;
		}

		var data = {
			action:      'pp_add_to_cart',
			add_to_cart: productParams['add-to-cart'],
			empty_cart:  'true',
			nonce:       wcopc.wcopc_nonce
		}

		$(this).ajax_add_remove_product( data, e );

	} );

	// Set response messages when the checkout is fully updated (because it would remove them if we set them before that)
	$( 'body' ).on( 'updated_checkout', function(){
		if ( response_messages.length > 0 ) {
			$( '#opc-messages' ).prepend( response_messages );

			if ( ! $( '#opc-messages' ).visible() ){
				$( 'html, body' ).animate( {
					scrollTop: ( $( '#opc-messages' ).offset().top - 50 )
				}, 500 );
			}

			response_messages = ''
		}
	});

	/* Function to add or remove product from cart via an ajax call */
	$.fn.ajax_add_remove_product = function( data, e ) {

		// Custom event for devs to hook into before posting of products for processing
		$('body').trigger( 'opc_add_remove_product', [ data ] );

		$.post( woocommerce_params.ajax_url, data, function( response ) {

			try {

				response = $.parseJSON(response);

				var inputs = $( '#opc-product-selection [data-add_to_cart]' );

				inputs.each( function( index, value ) {

					var product_id = $(this).data( 'add_to_cart' ),
						in_cart    = false;

					$.each( response.products_in_cart, function( cart_item_id, cart_item_data ) {
						if ( ( product_id == cart_item_id || product_id == cart_item_data.product_id ) ) {
							in_cart = true;
						}
					} );

					if ( $(this).prop( 'type' ) == 'number' ) {

						if ( in_cart ) {
							$(this).val( response.products_in_cart[ product_id ].quantity );
						} else {
							$(this).val(0);
						}

					} else if ( $(this).is( 'a, button' ) ) {

						if ( in_cart ) {
							$(this).parents( '.product-item' ).addClass( 'selected' );
						} else {
							$(this).parents( '.product-item' ).removeClass( 'selected' );
						}

					} else {

						if ( in_cart ) {
							$(this).prop( 'checked', true );
						} else {
							$(this).prop( 'checked', false );
						}
					}

				} );

			} catch ( err ) {

				$( '#opc-messages' ).prepend( response.messages );

				$( 'html, body' ).animate( {
					scrollTop: ( $( '#opc-messages' ).offset().top - 50 )
				}, 500);
			}

			$( '#opc-messages .woocommerce-error, #opc-messages .woocommerce-message, #opc-messages .woocommerce-info' ).remove();

			// Store messages for use when checkout has finished updating
			response_messages = response.messages;

			// Custom event for devs to hook into after products have been processed
			$('body').trigger( 'after_opc_add_remove_product', [ data, response ] );

			// Tell WooCommerce to update totals
			$('body').trigger( 'update_checkout' );

		} );

		e.preventDefault();
	};

	/* Only display the place order button when a product has been selected */
	showHidePlaceOrder();

	/* Append "Complete Order" anchor and "data-add_to_cart" attribute to single-product template buttons */
	initSingleProductTemplateButtons();

	/* Init custom order-review template quantity buttons */
	initOrderReviewQtyButtons();

	$( 'body' ).on( 'updated_checkout',function() {
		showHidePlaceOrder();

		/* Init custom order-review template quantity buttons */
		initOrderReviewQtyButtons();
	} );

	function initOrderReviewQtyButtons() {

		$( '#order_review.opc_order_review div.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );
	}

	function showHidePlaceOrder() {

		if ( $( '#order_review tbody' ).children().length>0 ) {
			$( '#place_order' ).show();
		} else {
			$( '#place_order' ).hide();
		}
	}

	function initSingleProductTemplateButtons() {

		$( '#opc-product-selection button.single_add_to_cart_button' ).each( function() {

			$(this).after( wcopc.wcopc_complete_order_prompt );
			$(this).attr( 'data-add_to_cart', $(this).closest( '.cart' ).find( 'input[name="add-to-cart"]' ).val() );
			$(this).data( 'add_to_cart', $(this).closest( '.cart' ).find( 'input[name="add-to-cart"]' ).val() );
		} );

	}

	function getUrlsParams( queryString ){

		var match,
			pl     = /\+/g,  // Regex for replacing addition symbol with a space
			search = /([^&=]+)=?([^&]*)/g,
			decode = function (s) { return decodeURIComponent(s.replace(pl, ' ')); };

		urlParams = {};

		while ( match = search.exec( queryString ) ) {
			urlParams[ decode( match[1] ) ] = decode( match[2] );
		}

		return urlParams;
	}

} );

/*! jQuery visible 1.1.0 teamdf.com/jquery-plugins | teamdf.com/jquery-plugins/license */
(function(d){d.fn.visible=function(e,i){var a=d(this).eq(0),f=a.get(0),c=d(window),g=c.scrollTop();c=g+c.height();var b=a.offset().top,h=b+a.height();a=e===true?h:b;b=e===true?b:h;return!!(i===true?f.offsetWidth*f.offsetHeight:true)&&b<=c&&a>=g}})(jQuery);
