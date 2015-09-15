(function($, window, document, undefined) {
	'use strict';
	var gtc,
		slNamespace = window.addthisnamespaces && window.addthisnamespaces['at40'] ? addthisnamespaces['at40']: 'addthis-smartlayers';
	$(function() {
		gtc.init();
	});

	gtc = {
		init: function() {
			var self = this;
			commonMethods.localStorageSettings({ namespace: slNamespace, method: "get" }, function(obj) {
				self.returninguser = $.type(obj) === "object" ? true: false;
				if(self.returninguser) {
					if(obj.closedFollowPopover) {
						self.closedFollowPopover = true;
					}
				}
				self.callPlugins().bindEvents().readyLogic(obj);
			});
		},
		enabledFollow: false,
		disabledClass: 'ui-state-disabled',
		rightArrowClass: 'ui-icon-triangle-1-e',
		downArrowClass: 'ui-icon-triangle-1-s',
		mouseenterAnimation: 'tada',
		fbDefaultText: 'YOUR-PROFILE',
		closedFollowPopover: false,
		defaults: {
			'#at40-accordion-follow-checkbox': false,
			'#at40-accordion-share-checkbox': true,
			'#at40-accordion-toaster-checkbox': true,
			'#at40-accordion-trending-checkbox': true,
			'input:text': '',
			'#follow-facebook': 'YOUR-PROFILE',
			'#left-share-position': true,
			'#at40-accordion-share-buttons': 5,
			'#at40-accordion-trending-textarea': 'Recommended for you:',
			'#at40-accordion-more-themes': 'transparent',
			'.at40-accordion-follow-checkbox': false,
			'#facebook-follow-checkbox': true
		},
		placeholderText: {
			'twitter': 'YOUR-USERNAME',
			'rss': 'YOUR-FEED',
			'default': 'YOUR-PROFILE'
		},
		callPlugins: function() {
			// Calls the iButton jQuery plugin that creates iPhone-style button toggles for all of the checkboxes
			$('.at40-accordion .at40-accordion-checkbox').iButton({allowRadioUncheck: true, enableDrag: false});
			// Calls the twitter bootstrap popover plugin on all of the follow text boxes
			$('.follow-tooltip').popover({ trigger: 'hover', animation: true });
			// Calls the twitter bootstrap tabs plugin
			if(!gtc.returninguser) {
				$(window).load(function() {
					var followPopover = $('li.at40-accordion-follow').popover({
						trigger: 'manual',
						html: 'true',
						animation: true,
						title : '<span class="text-info"><strong>Turn on Follow</strong></span>' + '<button type="button" class="close" style="margin-top:-5px;" title="Close" onclick="$(&quot;.at40-accordion-follow&quot;).popover(&quot;hide&quot;);">&times;</button>',
						content: 'If you have Facebook, Twitter, or other social profiles.'
					});
					setTimeout(function() {
						followPopover.popover('show');
					},2000);
				});
				commonMethods.localStorageSettings({
					namespace: slNamespace,
					method: "set",
					data: {
						returninguser: true
					}
				});
			}
			$('[data-toggle=tooltip]').tooltip();
			$.extend($.expr[":"], {
				'reallyvisible': function(elem) {
					var $elem = $(elem);
					return $elem.css('display') !== 'none' && $elem.css('visibility') !== 'hidden';
				},
				'hasValue': function(elem, index, match) {
					return $(elem).val() !== '';
				}
			});
			return this;
		},
		readyLogic: function(obj) {
			var elem, $elem, val, accordion = $('.at40-accordion');
			for(elem in obj) {
				if(obj.hasOwnProperty(elem)) {
					$elem = $(elem);
					val = obj[elem];
					if($elem.is('input:text') || $elem.is('textarea')) {
						if($elem.attr("id") != 'addthis_profile') {
							$elem.val(val);
						}
					}
					else if($elem.is(':checkbox')) {
						if(val === true) {
							if(!$elem.is(':checked')) {
								$elem.attr('checked', 'checked').trigger('change', true);
							}
						}
						else if(val === false) {
							if($elem.is(':checked')) {
								$elem.removeAttr('checked').trigger('change', true);
							}
						}
					}
					else if($elem.is(':radio')) {
						if(val === true) {
							if(!$elem.is(':checked')) {
								$elem.attr('checked', 'checked').trigger('click', true).trigger('change', true);
							}
						}
						else if(val === false) {
							if($elem.is(':checked')) {
								$elem.removeAttr('checked').trigger('click', true).trigger('change', true);
							}
						}
					}
					else if($elem.is('select')) {
						$elem.val(val).trigger('change', true);
					}
				}
			}
			accordion.find('input:text').add(accordion.find('textarea')).trigger('keyup', true);
			accordion.find('select').trigger('change', true);
			accordion.find('input:checkbox').trigger('change', true);
			return this;
		},
		disable: function(parentElem, internal, surface) {
			var self = this,
				textboxes = parentElem.find('input:text'),
				radiobuttons = parentElem.find('input:radio'),
				checkboxes = parentElem.find('input:checkbox'),
				textareas = parentElem.find('textarea'),
				selects = parentElem.find('select'),
				icon = parentElem.prev('.at40-accordion-row').find('.at40-accordion-icon');
			parentElem.addClass(self.disabledClass);
			textboxes.attr('readonly', 'readonly');
			radiobuttons.add(textareas).add(checkboxes).attr('disabled', 'disabled');
			textboxes.add(textareas).addClass('no-selection');
			if(selects.length && selects.data('selectBox-selectBoxIt')) {
				selects.data('selectBox-selectBoxIt').disable();
			}
			$('.follow-tooltip').popover('destroy');

			if(surface === 'follow') {
				$('.facebookAndTwitterPopover').popover('hide');
			}
			if(!internal) {
				parentElem.slideUp();
				icon.removeClass(self.downArrowClass).addClass(self.rightArrowClass);
			}
		},
		enable: function(parentElem, internal, surface) {
			var self = this,
				device = $('body').attr('data-device') || 'desktop',
				textboxes = parentElem.find('input:text'),
				radiobuttons = parentElem.find('input:radio'),
				checkboxes = parentElem.find('input:checkbox'),
				textareas = parentElem.find('textarea'),
				selects = parentElem.find('select'),
				icon = parentElem.prev('.at40-accordion-row').find('.at40-accordion-icon');
			if(surface === 'share' && device !== 'desktop') return;
			parentElem.removeClass(self.disabledClass);
			textboxes.removeAttr('readonly');
			radiobuttons.add(textareas).add(checkboxes).removeAttr('disabled');
			textboxes.add(textareas).removeClass('no-selection');
			if(selects.length && selects.data('selectBox-selectBoxIt')) {
				selects.data('selectBox-selectBoxIt').enable();
			}
			$('.follow-tooltip').popover({ trigger: 'hover', animation: true });
			if(!internal) {
				parentElem.slideDown();
				icon.removeClass(self.rightArrowClass).addClass(self.downArrowClass);
			}
		},
		events: {
			'.at40-accordion-icon-and-text': {
				'click': function(e, internal) {
					var self = gtc,
						row = $(this).parent('.at40-accordion-row'),
						service = row.attr('data-type'),
						hiddenItem = $('.at40-accordion-' + service + '-hidden'),
						accordionCheckbox = row.find('.at40-accordion-checkbox'),
						facebookCheckbox = $('#facebook-follow-checkbox'),
						icon = $(this).find('.at40-accordion-icon'),
						fb = $('#follow-facebook'),
						fbVal = fb.val(),
						fbLength = fbVal.length,
						hiddenItemIsDisabled = hiddenItem.hasClass(self.disabledClass),
						hiddenItemIsVisible = hiddenItem.is(':visible'),
						accordionIsChecked = accordionCheckbox.is(':checked'),
						device = $('body').attr('data-device') || 'desktop';
					if(internal) {
						hiddenItem.slideDown();
						icon.removeClass(self.rightArrowClass).addClass(self.downArrowClass);
					}
					else if(hiddenItem.length && hiddenItemIsVisible) {
						hiddenItem.slideUp();
						icon.removeClass(self.downArrowClass).addClass(self.rightArrowClass);
					}
					else if(hiddenItem.length && !hiddenItemIsVisible) {
						hiddenItem.slideDown();
						icon.removeClass(self.rightArrowClass).addClass(self.downArrowClass);
					}
					if(accordionCheckbox.length) {
						if(accordionIsChecked && hiddenItemIsDisabled && (device === 'desktop' && service !== 'share')) {
							hiddenItem.removeClass(self.disabledClass);
							self.enable(hiddenItem, internal);
						}
						else if(!accordionIsChecked && !hiddenItemIsDisabled) {
							self.disable(hiddenItem, internal);
						}
					}
					if(service === 'follow' && !hiddenItemIsDisabled) {
						$('li.at40-accordion-follow').popover('destroy');
					}
					if(row.hasClass('at40-accordion-follow') && !hiddenItem.hasClass(self.disabledClass)) {
						if(accordionCheckbox.is(':checked')) {
								if((!fbLength || fbVal === self.fbDefaultText)) {
									if(!gtc.closedFollowPopover) {
										$('.facebookAndTwitterPopover').popover('destroy').popover({
											trigger: 'manual',
											html: 'true',
											title : '<span class="text-info"><strong>Add Social Profiles</strong></span>' + '<button type="button" class="close" style="margin-top:-5px;" title="Close" onclick="$(&quot;.facebookAndTwitterPopover&quot;).popover(&quot;hide&quot;).trigger(&quot;hide&quot;)">&times;</button>'
										});
									}
									if(!self.enabledFollow) {
										if((!fbLength || fbVal === self.fbDefaultText) && facebookCheckbox.is(':checked')) {
											row.promise().done(function() {
												self.backgroundAnimation({
													elem: fb,
													start: {
														wait: 500,
														duration: 500,
														props: {
															backgroundColor: '#c60f13',
															borderColor: '#CCC'
														}
													},
													end: {
														wait: 400,
														duration: 400,
														props: {
															backgroundColor: '#fff',
															borderColor: '#CD0A0A'
														}
													}
												});
											});
										}
								}
								self.enabledFollow = true;
								if(!gtc.closedFollowPopover && fbVal === self.fbDefaultText) {
									$('.facebookAndTwitterPopover').popover('show');
								}
							}
							$('.at40-accordion-follow-hidden input:text').trigger('keyup', true);
						} else {
							gtc.removePopover();
						}
					}
					if(fbVal !== gtc.fbDefaultText) {
						fb.css('borderColor', '#CCC');
					}
					else if(fbVal === gtc.fbDefaultText && facebookCheckbox.is(':checked')) {
						fb.css('borderColor', '#CD0A0A');
					}
				}
			},
			'.at40-accordion-checkbox': {
				'change': function(e, internal) {
					var self = gtc,
						checkbox = $(this),
						surface = checkbox.attr('data-surface'),
						row = $('.at40-accordion-' + surface),
						hiddenItem = $('.at40-accordion-' + surface + '-hidden'),
						fb = $('#follow-facebook'),
						fbLength = fb.val().length,
						device = $('body').attr('data-device') || 'desktop',
						previewImg = $('.' + device + '-device[data-previewservice=' + surface + ']'),
						allPreviewImgs = $('[data-previewservice=' + surface + ']'),
						follow = $('.at40-follow-horizontal'),
						visibleFollowIcons;
					if(checkbox.is(':checked')) {
						self.enable(hiddenItem, internal, surface);
						allPreviewImgs.fadeIn().promise().done(function() {
							if(!internal) {
								previewImg.addClass('animated ' + gtc.mouseenterAnimation + ' animate-pulse-speed');
								$('html, body').promise().done(function() {
									setTimeout(function() {
										if(previewImg.is(':reallyvisible')) {
											previewImg.tooltip('show');
										}
									},0);
								});
							}
							if(surface === 'follow') {
								visibleFollowIcons = $('.at40-follow-horizontal .at40-follow-icon:visible');
								if(!visibleFollowIcons.length) {
									follow.hide();
								}
							}
						});
					} else {
						self.disable(hiddenItem, internal, surface);
						allPreviewImgs.removeClass('animated ' + gtc.mouseenterAnimation + ' animate-pulse-speed').fadeOut().tooltip('hide');
					}
					if(internal === undefined) {
						if(surface) {
							if(checkbox.is(':checked')) {
							} else {
							}
						}
					}
					$('#at40-accordion-more-themes').trigger('change');
				}
			},
			'.at40-accordion-follow .at40-accordion-checkbox': {
				'change': function(e, internal) {
					if(internal) {
						return;
					}
					$('.at40-accordion-follow-popover').popover('destroy');
					var self = gtc,
						checkbox = $(this),
						row = $(this).parent('.at40-accordion-row'),
						hiddenItem = row.next('.at40-accordion-hidden'),
						fb = $('#follow-facebook'),
						fbVal = fb.val(),
						fbLength = fb.val().length;
					if(checkbox.is(':checked')) {
						if(!self.enabledFollow && !fbLength || fbVal === self.fbDefaultText) {
							$('.at40-accordion-follow .at40-accordion-icon-and-text').trigger('click', true);
						}
					}
				}
			},
			'.show-more-follow-options-link': {
				'click': function(e) {
					e.preventDefault();
					var showMoreOptions = $('.show-more-follow-options'),
						showLessOptionsLink = $('.show-less-follow-options-link'),
						followCheckbox = $('#at40-accordion-follow-checkbox');
					if(followCheckbox.is(':checked')) {
						if(!showMoreOptions.is(':reallyvisible')) {
							showMoreOptions.slideDown();
							$(this).hide();
							showLessOptionsLink.show();
						}
					}
				}
			},
			'.show-less-follow-options-link': {
				'click': function(e) {
					e.preventDefault();
					var showMoreOptions = $('.show-more-follow-options'),
						showMoreOptionsLink = $('.show-more-follow-options-link'),
						followCheckbox = $('#at40-accordion-follow-checkbox');
					if(followCheckbox.is(':checked')) {
						if(showMoreOptions.is(':reallyvisible')) {
							showMoreOptions.slideUp();
							$(this).hide();
							showMoreOptionsLink.show();
						}
					}
				}
			},
			'.follow-tooltip': {
				click: function(e) {
					e.preventDefault();
				},
				'mouseover': function() {
					gtc.removePopover();
				}
			},
			'.at40-accordion-trending-textarea': {
				'keyup': function(e) {
					$('.at40-trending-text').text($(this).val());
				}
			},
			'#right-share-position': {
				'change': function(e, internal) {
					$('.at40-sharing-vertical').css('right', '0');
				}
			},
			'#left-share-position': {
				'change': function(e, internal) {
					$('.at40-sharing-vertical').css('right', 'auto');
				}
			},
			'#at40-accordion-share-buttons': {
				'change': function(e, internal) {
					var val = +$(this).val(),
						buttons = $('.at40-sharing-vertical .at40-share-icon'),
						btns;

					if(val === 1) {
						btns = buttons.eq(-1);
						btns.fadeIn();
						buttons.not(btns).fadeOut();
					}
					else {
						btns = buttons.slice(0, val - 1).add(buttons.eq(-1));
						btns.fadeIn();
						buttons.not(btns).fadeOut();
					}
				}
			},
			'.at40-accordion-follow-hidden input[data-service]': {
				'keyup change': function(e, internal) {
					var isCheckbox = $(this).is(':checkbox'),
						service = $(this).attr('data-service'),
						company = $(this).attr('data-company'),
						accordionCheckbox = $('#at40-accordion-follow-checkbox'),
						accordionCheckboxIsChecked = accordionCheckbox.is(':checked'),
						followCheckbox = $('#' + service + (company ? '-company-' : '-') + 'follow-checkbox'),
						followCheckboxIsChecked = followCheckbox.is(':checked'),
						follow = $('.at40-follow-horizontal'),
						followItems = $('.at40-accordion-follow-hidden input:text').filter(':hasValue'),
						textbox = company ? $('#follow-' + service + '-company') : $('#follow-' + service),
						textboxVal = textbox.val(),
						dynamicClass = '.at40-follow-icon-' + service,
						dynamicElem,
						dynamicElemIsVisible,
						currentPlaceholderText = gtc.placeholderText[service] || gtc.placeholderText['default'],
						checkboxesAreChecked = followCheckboxIsChecked && accordionCheckboxIsChecked,
						visibleFollowIcons;
					if(company) {
						dynamicClass += '-company';
					}
					dynamicElem = follow.find(dynamicClass);
					dynamicElemIsVisible = dynamicElem.is(':reallyvisible');

					if(checkboxesAreChecked) {
						textbox.removeClass(gtc.disabledClass);
					} else {
						textbox.addClass(gtc.disabledClass);
					}
					if(checkboxesAreChecked && textboxVal.length && !dynamicElemIsVisible) {
						dynamicElem.fadeIn().css('display', 'inline-block');
						follow.show();
					}
					if(!followCheckboxIsChecked || !textboxVal.length && !internal) {
						dynamicElem.fadeOut(400).promise().done(function() {
							visibleFollowIcons = $('.at40-follow-horizontal .at40-follow-icon:visible');
							if(!visibleFollowIcons.length) {
								follow.hide();
							}
						});
					}
					if(checkboxesAreChecked && (!textboxVal.length || textboxVal === currentPlaceholderText) && !textbox.is(':focus') && isCheckbox) {
						if(!internal) {
							textbox.val(currentPlaceholderText).css('borderColor', '#CD0A0A').select();
						}
						dynamicElem.fadeIn().css('display', 'inline-block');
					} else {
						textbox.attr('placeholder', '').css('borderColor', '#CCC');
					}
				}
			},
			'#at40-accordion-more-themes': {
				'change device-change': function() {
					var device = $('body').attr('data-device'),
						desktopTrending = $('.at40-desktop-trending'),
						tabletTrending = $('.at40-tablet-trending'),
						phoneTrending = $('.at40-phone-trending'),
						toaster = $('.at40-toaster-img'),
						follow = $('.at40-follow-horizontal'),
						visibleFollowItems = follow.find('.at40-follow-icon:reallyvisible'),
						sharing = $('.at40-sharing-vertical'),
						isFollowChecked = $('#at40-accordion-follow-checkbox').is(':checked'),
						isShareChecked = $('#at40-accordion-share-checkbox').is(':checked'),
						isToasterChecked = $('#at40-accordion-toaster-checkbox').is(':checked'),
						isTrendingChecked = $('#at40-accordion-trending-checkbox').is(':checked'),
						tabletShare = $('.at40-tablet-share'),
						tabletFollow = $('.at40-tablet-follow'),
						phoneShare = $('.at40-phone-share'),
						phoneFollow = $('.at40-phone-follow'),
						tabletDivider = $('.at40-tablet-share-divider, .at40-tablet-follow-divider'),
						phoneDivider = $('.at40-phone-share-divider, .at40-phone-follow-divider'),
						tabletArrow = $('.at40-tablet-arrow'),
						phoneArrow = $('.at40-phone-arrow'),
						theme = $(this).val();
					if(theme === 'light' || theme === 'transparent') {
						desktopTrending.attr('src', smart_layer_params.img_base+'surface_recommended.png');
						tabletTrending.attr('src', smart_layer_params.img_base+'surface_recommended.png');
						phoneTrending.attr('src', smart_layer_params.img_base+'preview_phone_recommended.png');
						toaster.attr('src', smart_layer_params.img_base+'preview_toaster.png');
						if(theme === 'transparent') {
							follow.add(sharing).removeClass('dark-theme').removeClass('gray-theme').removeClass('light-theme');
						} else if(theme === 'light') {
							follow.add(sharing).removeClass('dark-theme').removeClass('gray-theme').addClass('light-theme');
						}
						tabletDivider.add(phoneDivider).removeClass('dark-divider').removeClass('light-divider').addClass('transparent-divider');
					}
					else if(theme === 'gray') {
						desktopTrending.attr('src', smart_layer_params.img_base+'surface_recommended_gray.png');
						tabletTrending.attr('src', smart_layer_params.img_base+'surface_recommended_gray.png');
						phoneTrending.attr('src', smart_layer_params.img_base+'preview_phone_recommended_light.png');
						toaster.attr('src', smart_layer_params.img_base+'preview_toaster.png');
						follow.add(sharing).removeClass('dark-theme').removeClass('light-theme').addClass('gray-theme');
						tabletDivider.add(phoneDivider).removeClass('dark-divider').removeClass('transparent-divider').addClass('light-divider');
					}
					else if(theme === 'dark') {
						desktopTrending.attr('src', smart_layer_params.img_base+'surface_recommended_dark.png');
						tabletTrending.attr('src', smart_layer_params.img_base+'surface_recommended_dark.png');
						phoneTrending.attr('src', smart_layer_params.img_base+'preview_phone_recommended_dark.png');
						toaster.attr('src', smart_layer_params.img_base+'preview_toaster_dark.png');
						follow.add(sharing).removeClass('gray-theme').removeClass('light-theme').addClass('dark-theme');
						tabletDivider.add(phoneDivider).removeClass('transparent-divider').removeClass('light-divider').addClass('dark-divider');
					}
					theme = theme === 'light' ? 'transparent' : theme;
					if(!visibleFollowItems.length && device === 'desktop') {
						follow.hide();
					}
					if(!isFollowChecked && !isShareChecked) {
						tabletDivider.add(phoneDivider).hide();
						tabletArrow.add(phoneArrow).hide();
					} else if(isFollowChecked && isShareChecked) {
						tabletFollow.removeClass('at40-tablet-follow-full').attr('src', smart_layer_params.img_base+'half_tablet_' + theme + '_follow.png').show();
						phoneFollow.removeClass('at40-phone-follow-full').attr('src', smart_layer_params.img_base+'half_mobile_' + theme + '_follow.png').show();
						tabletShare.attr('src', smart_layer_params.img_base+'half_tablet_' + theme + '_share.png').removeClass('at40-tablet-share-full').show();
						phoneShare.removeClass('at40-phone-share-full').attr('src', smart_layer_params.img_base+'half_mobile_' + theme + '_share.png').show();
						tabletDivider.add(phoneDivider).show();
						tabletArrow.attr('src', smart_layer_params.img_base+'tablet_tab_' + theme + '.png').show();
						phoneArrow.attr('src', smart_layer_params.img_base+'mobile_tab_' + theme + '.png').show();
					} else {
						tabletArrow.add(phoneArrow).hide();
						tabletDivider.add(phoneDivider).hide();
						if(isFollowChecked && !isShareChecked) {
							tabletShare.add(phoneShare).hide();
							tabletFollow.addClass('at40-tablet-follow-full').attr('src', smart_layer_params.img_base+'tablet_' + theme + '_follow.png').show();
							phoneShare.removeClass('at40-phone-share-full').hide();
							phoneFollow.addClass('at40-phone-follow-full').attr('src', smart_layer_params.img_base+'mobile_' + theme + '_follow.png').show();
						} else if(!isFollowChecked && isShareChecked) {
							tabletShare.attr('src', smart_layer_params.img_base+'tablet_' + theme + '_share.png').addClass('at40-tablet-share-full').show();
							tabletFollow.removeClass('at40-tablet-follow-full').hide();
							phoneShare.attr('src', smart_layer_params.img_base+'mobile_' + theme + '_share.png').addClass('at40-phone-share-full').show();
							phoneFollow.removeClass('at40-phone-follow-full').hide();
						}
					}
					if(!isTrendingChecked) {
						$('[data-previewservice=trending]').hide();
					}
					if(!isFollowChecked) {
						$('[data-previewservice=follow]').hide();
					}
					if(!isShareChecked) {
						$('[data-previewservice=share]').hide();
					}
					if(!isToasterChecked) {
						$('[data-previewservice=toaster]').hide();
					}
					$('.follow-tooltip').popover({ trigger: 'hover', animation: true });
				}
			},
			'.at40-accordion-row': {
				'mouseenter': function() {
					var service = $(this).attr('data-type'),
						device = $('body').attr('data-device') || 'desktop',
						previewImg = $('.' + device + '-device[data-previewservice=' + service + ']:visible');
					if(((service !== 'follow') || (service === 'follow' && $('.at40-follow-horizontal .at40-follow-icon:visible').length))) {
						previewImg.addClass('animated ' + gtc.mouseenterAnimation + ' animate-pulse-speed').tooltip('show');
					}
				},
				'mouseleave': function() {
					var service = $(this).attr('data-type'),
						previewImg = $('[data-previewservice=' + service + ']').filter(':reallyvisible');
						previewImg.removeClass('animated ' + gtc.mouseenterAnimation + ' animate-pulse-speed').tooltip('hide');
				}
			},
			'#follow-facebook': {
				'keyup': function(e, internal) {
					if(internal) {
						return;
					}
					var elem = $(this),
						val = elem.val(),
						service = elem.attr('data-service'),
						facebookCheckbox = $('#facebook-follow-checkbox');

					if(service === 'facebook' && val !== gtc.fbDefaultText) {
						elem.css('borderColor', '#CCC');
					}
					else if(service === 'facebook' && val === gtc.fbDefaultText && facebookCheckbox.is(':checked')) {
						elem.css('borderColor', '#CD0A0A');
					}
				}
			},
			'#facebook-follow-checkbox': {
				'change': function(e, internal) {
						if(!$(this).is(':checked')) {
							gtc.removePopover();
						}
				}
			},
			'.at40-accordion input, .at40-accordion select, .at40-accordion textarea': {
				'keyup change select': function(e) {
					var dynamicObj = {},
						$elem = $(this);
						dynamicObj['#' + $elem.attr('id')] = $elem.is('input:radio') || $elem.is('input:checkbox') ? $elem.is(':checked') : $elem.attr('value');
					commonMethods.localStorageSettings({
						namespace: slNamespace,
						method: 'set',
						data: dynamicObj
					});
					if($elem.is('input:radio') && $elem.is(':checked')) {
						$elem.siblings('input:radio').attr('checked', false).trigger('keyup');
					}
				}
			},
			'.facebookAndTwitterPopover': {
				'hide': function() {
					gtc.closedFollowPopover = true;
					commonMethods.localStorageSettings({
						namespace: slNamespace,
						method: 'set',
						data: {
							closedFollowPopover: true
						}
					});
				}
			},
			'.at40-restore-default-options': {
				'click': function(ev) {
					var defaults = gtc.defaults;
					commonMethods.localStorageSettings({
						namespace: slNamespace,
						method: "remove"
					});
					gtc.readyLogic(defaults);
					$('.at40-accordion-hidden').not('.at40-accordion-more-hidden').slideUp();
					$('.at40-accordion-more-hidden').slideDown();
					ev.preventDefault();
				}
			},
			'input.save-profile': {
				'click': function(ev) {
						gtc.generateCode();
					}
			},
			'.at40-device-type-buttons .btn': {
				'click': function(ev) {
					ev.preventDefault();
					if($(this).hasClass('btn-primary')) return;
					var currentButton = $(this),
						device = currentButton.attr('data-device'),
						desktopImg = $('.at40-browser-img'),
						tabletImg = $('.at40-tablet-img'),
						phoneImg = $('.at40-phone-img'),
						desktopDevice = $('.desktop-device'),
						tabletDevice = $('.tablet-device'),
						phoneDevice = $('.phone-device'),
						tabletBackground = $('.at40-tablet-background'),
						trendingText = $('.at40-trending-text'),
						phoneBackground = $('.at40-phone-background'),
						shareAccordionCheckbox = $('#at40-accordion-share-checkbox'),
						isShareChecked = shareAccordionCheckbox.is(':checked'),
						shareHiddenContent = $('.at40-accordion-share-hidden'),
						toaster = $('.at40-accordion-toaster'),
						toasterHiddenContent = $('.at40-accordion-toaster-hidden'),
						toasterIcon = $('.at40-accordion-toaster .at40-accordion-icon'),
						previewArea = $('#preview');
					$('.at40-device-type-buttons .btn').not(currentButton).removeClass('btn-primary');
					$('body').attr('data-device', device);

					previewArea.hide();
					if(!currentButton.hasClass('btn-primary')) {
						currentButton.addClass('btn-primary');
						if(device === 'desktop') {
							tabletDevice.add(phoneDevice).addClass('smartlayers-hidden');
							tabletImg.add(phoneImg).addClass('hidden-off-screen');
							desktopImg.removeClass('hidden-off-screen');
							trendingText.removeClass('at40-trending-text-tablet').removeClass('at40-trending-text-phone');
							desktopDevice.removeClass('smartlayers-hidden');
						} else if(device === 'tablet') {
							desktopDevice.add(phoneDevice).addClass('smartlayers-hidden');
							desktopImg.add(phoneImg).addClass('hidden-off-screen');
							tabletImg.removeClass('hidden-off-screen');
							trendingText.removeClass('at40-trending-text-phone').addClass('at40-trending-text-tablet');
							tabletDevice.removeClass('smartlayers-hidden');
						} else if(device === 'phone') {
							desktopDevice.add(tabletDevice).add(trendingText).addClass('smartlayers-hidden');
							desktopImg.add(tabletImg).addClass('hidden-off-screen');
							phoneImg.removeClass('hidden-off-screen');
							trendingText.removeClass('at40-trending-text-tablet').addClass('at40-trending-text-phone');
							phoneDevice.add(trendingText).not(phoneBackground).removeClass('smartlayers-hidden');
						}
			            if(device === 'tablet' || device === 'phone') {
			              if(isShareChecked) {
			                gtc.disable(shareHiddenContent, true);
			                $('[data-device=' + device + '] .at40-accordion-share-hidden').tooltip({
			                    trigger: 'hover',
			                    animation: true,
			                    title : 'Desktop Only',
			                    placement: 'right'
			                });
			              }
			              toaster.add(toasterHiddenContent).hide();
			            } else if(device === 'desktop') {
			              $('[data-device=' + device + '] .at40-accordion-share-hidden').tooltip('destroy');
			              if(isShareChecked) {
			                gtc.enable(shareHiddenContent, true);
			              }
			              toaster.show();
			              toasterIcon.removeClass(gtc.downArrowClass).addClass(gtc.rightArrowClass);
			            }
					}
					previewArea.children().hide();
					previewArea.show();
					previewArea.children('.at40-browser-img').show().promise().done(function() {
						previewArea.children().fadeIn(200);
						if(device !== 'desktop') {
							$('.at40-preview-trending-img').hide();
						}
						$('#at40-accordion-more-themes').trigger('device-change');
					});
				}
			},
			'a[href=#]': {
				'click': function(ev) {
					ev.preventDefault();
				}
			},
		},
		backgroundAnimation: function(obj) {
			setTimeout(function() {
				obj.elem.animate(obj.start.props, obj.start.duration, function() {
					setTimeout(function() {
						obj.elem.animate(obj.end.props, obj.end.duration);
					}, obj.end.wait);
				});
			}, obj.start.wait);
		},
		removePopover: function() {
			if(gtc.enabledFollow) {
				$('.facebookAndTwitterPopover').popover('destroy');
			}
		},
		bindEvents: function() {
			var x,
				events = this.events;
			for(x in events) {
				if(events.hasOwnProperty(x)) {
					$(x).on(events[x]);
				}
			}
			return this;
		},
		generateCode: function() {
			var pubId; 
			if($("#addthis_profile").val() != "") {
				pubId = $("#addthis_profile").val();
			}
			else {
				pubId = $("#pub").val();
			}
			// Setting the generated code template property

			/* To make it work on servers with ASP style <% %> enabled, we change Lo-Dash 
			   template delimiter <% %> to <@ @>. */
		    _.templateSettings = {
  				interpolate: /\<\@\=(.+?)\@\>/g,
  				evaluate: /\<\@(.+?)\@\>/g
			};  
			var leftSharePosition = $('#left-share-position'),
				template = _.template($.trim($("#generated-code").html()), {
					theme: $('#at40-accordion-more-themes').val(),
					followIsTurnedOn: $('#at40-accordion-follow-checkbox').is(':checked'),
					shareIsTurnedOn: $('#at40-accordion-share-checkbox').is(':checked'),
					toasterIsTurnedOn: $('#at40-accordion-toaster-checkbox').is(':checked'),
					trendingIsTurnedOn: $('#at40-accordion-trending-checkbox').is(':checked'),
					sharePosition: leftSharePosition.is(':checked') ? "left": "right",
					numPreferredServices: $('#at40-accordion-share-buttons').val(),
					followServices: (function() {
						var services = "[",
							followCheckboxes = $.grep($('.at40-accordion-follow-checkbox:checked'), function(elem) {
								var currentService = $(elem).attr('data-service'),
								company = $(elem).attr('data-company'),
								currentId = company === 'true' ? $('#follow-' + currentService + '-company').val() : $('#follow-' + currentService).val();
								return currentId !== '' && currentId !== 'YOUR-FEED' && currentId !== 'YOUR-PROFILE' && currentId !== 'YOUR-USERNAME';
							}),
							currentService = "",
							currentId = "",
							company = "";
						if(followCheckboxes.length) {
							$(followCheckboxes).each(function(iterator, value) {
								currentService = $(this).attr('data-service');
								company = $(this).attr('data-company');
								currentId = company === 'true' ? $('#follow-' + currentService + '-company').val() : $('#follow-' + currentService).val();
								if(currentService === 'rss') {
									currentId = 'http://' + currentId;
								} else if(currentService === 'gplus') {
									currentService = 'google_follow';
								}
								if(iterator < followCheckboxes.length - 1) {
										if(company === 'true') {
											services += "\r\n        {'service': '" + currentService + "', 'id': '" + currentId + "', 'usertype': 'company'},";
										} else {
											services += "\r\n        {'service': '" + currentService + "', 'id': '" + currentId + "'},";
										}
								} else {
									if(company === 'true') {
										services += "\r\n        {'service': '" + currentService + "', 'id': '" + currentId + "', 'usertype': 'company'}";
										services += "\r\n      ]";
									} else {
										services += "\r\n        {'service': '" + currentService + "', 'id': '" + currentId + "'}";
										services += "\r\n      ]";
									}
								}
							});
						} else {
							services += ']';
						}
						return services;
					}()),
					trendingLabel: $('#at40-accordion-trending-textarea').val()
				});
			$.ajax(
		            {"url" : smart_layer_params.wp_ajax_url,
		             "type" : "post",
		             "data" : {"action" : 'save_smart_layer_settings',
		                      "value" : template,
		                      "profileId" : $('#addthis_profile').val(),
		                  },
		             "success": function(data) {
		             },
		             "complete" :function(data) {
		            	 $('#smartlayers-getthecode').submit();
		             },
		             
		         });
			$('textarea#txt').val(template);
		}
	};
	
}(window.jQuery, window, document));
