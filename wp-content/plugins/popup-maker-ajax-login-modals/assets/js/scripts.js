(function ($) {
    "use strict";

    $.fn.serializeObject = function () {
        var o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    function form_ajax_request(data, callback) {
        if (typeof callback !== 'function') {
            callback = function () { return; };
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: data,
            success: callback
        });
    }

    var login = $(),
        registration = $(),
        recovery = $(),
        login_form = $(),
        registration_form = $(),
        recovery_form = $(),
        registration_form_submit;

    $('.popmake').on('popmakeInit', function () {

        var $this = $(this),
            settings = $this.data('popmake'),
            ajax_login = settings.meta.ajax_login,
            ajax_registration = settings.meta.ajax_registration,
            ajax_recovery = settings.meta.ajax_recovery;

        if (ajax_login !== undefined && ajax_login.enabled && $('#ajax-login-form').length) {

            login = $('.popmake-login-form');
            login_form = $('form', login);

            if (login_form.attr('action').indexOf('/popup/') > -1) {
                login_form.attr('action', '');
            }

            login_form.on('submit', function (event) {

                var message = $('p.message', login),
                    ajaxData = $.extend(login_form.serializeObject(), {
                        'action': 'ajaxlogin',
                        'popup_id': settings.id,
                        'login': true,
                        'nonce': popmake_alm.nonce
                    });

                event.preventDefault();

                login_form.find('input.error').removeClass('error').next('.message.error').remove();

                if (!message.length) {
                    $('.popmake-alm-footer-links', login).before('<p class="message notice"></p>');
                    message = $('p.message', login);
                }

                message.removeClass('error').removeClass('success').html(popmake_alm.I10n.login_loading_text).slideDown();

                form_ajax_request(ajaxData, function (results) {
                    // Check the returned data message. If we logged in successfully, then let our users know and remove the popup window.
                    if (results.success === true) {
                        message.addClass('success').html(results.message);
                        setTimeout(function () {
                            message.slideUp(function () {
                                $this
                                    .on('popmakeBeforeClose', function () {
                                        if (ajax_login.disable_redirect === null) {
                                            window.location.href = ajax_login.redirect_url !== '' ? ajax_login.redirect_url : window.location.href;
                                        }
                                    })
                                    .popmake('close');
                            });
                        }, 5000);
                    } else {
                        message.addClass('error').html(results.message);
                    }
                });

            });

        }

        if (ajax_registration !== undefined && ajax_registration.enabled && $('.popmake-registration-form form').length) {

            registration = $('.popmake-registration-form');
            registration_form = $('form', registration);

            if (registration_form.attr('action').indexOf('/popup/') > -1) {
                registration_form.attr('action', '');
            }

            registration_form_submit = function (event) {

                var message = $('p.message', registration),
                    ajaxData = $.extend(registration_form.serializeObject(), {
                        'action': 'ajaxlogin',
                        'popup_id': settings.id,
                        'register': true,
                        'nonce': popmake_alm.nonce
                    }),
                    error_field;

                event.preventDefault();

                registration_form.find('input.error').removeClass('error').next('.message.error').remove();

                if (!message.length) {
                    $('.popmake-alm-footer-links', registration).before('<p class="message notice"></p>');
                    message = $('p.message', registration);
                }

                message.removeClass('error').removeClass('success').html(popmake_alm.I10n.registration_loading_text).slideDown();

                if ($('#ajax_registration_pass', registration_form).length && $('#ajax_registration_pass', registration_form).val() !== $('#ajax_registration_confirm', registration_form).val()) {
                    message.addClass('error').html('Passwords don\'t match.');
                    return;
                }
console.log(ajaxData);
                form_ajax_request(ajaxData, function (results) {
                    // Check the returned data message. If we logged in successfully, then let our users know and remove the popup window.
                    if (results.success === true) {

console.log(results);
if (ajaxData.register == true) {
    analytics.track('User registered',
        {
            'user_id': results.user_id,
            'traits': {
                'username' : ajaxData.user_login,
                'email'    : ajaxData.user_email,
                'firstName': ajaxData.fname,
                'lastName' : ''
            }
        }
    );
}

                        if (results.form !== undefined) {
                            registration_form.replaceWith(results.form);
                            registration_form = $('form', registration);
                        } else if (results.message !== undefined) {
                            message.addClass('success').html(results.message);
                            setTimeout(function () {
                                message.slideUp(function () {
                                    $this
                                        .on('popmakeBeforeClose', function () {
                                            if (ajax_registration.disable_redirect === null) {
                                                window.location.href = ajax_registration.redirect_url !== '' ? ajax_registration.redirect_url : window.location.href;
                                            }
                                        })
                                        .popmake('close');
                                });
                            }, 5000);
                        } else {
                            if (ajax_registration.disable_redirect === null) {
                                window.location.href = ajax_registration.redirect_url !== '' ? ajax_registration.redirect_url : window.location.href;
                            }
                        }
                    } else {
                        message.addClass('error').html(results.message);
                        if (results.field !== undefined) {
                            error_field = $('[name="' + results.field + '"]', registration_form) || null;
                            if (error_field.length) {

                                $('html, body').animate({
                                    scrollTop: error_field.offset().top - 100
                                }, 1000, function () {
                                    error_field.addClass('error').focus();
                                    message.insertAfter(error_field);
                                });

                            }
                        } else if (results.form !== undefined) {
                            registration_form.replaceWith(results.form);
                            registration_form = $('form', registration);
                            message.slideUp();
                            registration_form.on('submit', registration_form_submit);
                            error_field = $('.error, .wppb-field-error', registration_form).eq(0);
                            $('html, body').animate({
                                scrollTop: error_field.offset().top - 100
                            }, 1000, function () {
                                error_field.addClass('error').focus();
                            });
                        }
                    }
                });

            };

            registration_form.on('submit', registration_form_submit);

        }

        if (ajax_recovery !== undefined && $('#ajax-recovery-form').length) {

            recovery = $('.popmake-recovery-form');
            recovery_form = $('form', recovery);

            if (recovery_form.attr('action').indexOf('/popup/') > -1) {
                recovery_form.attr('action', '');
            }

            recovery_form.on('submit', function (event) {

                var message = $('p.message', recovery),
                    ajaxData = $.extend(recovery_form.serializeObject(), {
                        'action': 'ajaxlogin',
                        'popup_id': settings.id,
                        'recovery': true,
                        'nonce': popmake_alm.nonce
                    });

                event.preventDefault();

                login_form.find('input.error').removeClass('error').next('.message.error').remove();

                if (!message.length) {
                    $('.popmake-alm-footer-links', recovery).before('<p class="message notice"></p>');
                    message = $('p.message', recovery);
                }

                message.removeClass('error').removeClass('success').html(popmake_alm.I10n.recovery_loading_text).slideDown();

                form_ajax_request(ajaxData, function (results) {
                    // Check the returned data message. If we logged in successfully, then let our users know and remove the popup window.
                    if (results.success === true) {

                        message.addClass('success').html(results.message);

                        setTimeout(function () {
                            message.slideUp(function () {
                                $this
                                    .on('popmakeBeforeClose', function () {
                                        if (ajax_recovery.disable_redirect === null) {
                                            window.location.href = ajax_recovery.redirect_url !== '' ? ajax_recovery.redirect_url : window.location.href;
                                        }
                                    })
                                    .popmake('close');
                            });
                        }, 2000);
                    } else {
                        message.addClass('error').html(results.message);
                    }
                });

            });

        }

        if (ajax_login !== undefined && $('#ajax-login-form').length && ajax_login.force_login) {
            jQuery.fn.popmake.last_open_trigger = 'Force Login Popup ID-' + settings.id;
            login.show();
            registration.hide();
            recovery.hide();
            $this
                .on('popmakeSetupClose', function () {
                    var $close = $('> .' + settings.close.attr.class, $this);
                    $close.hide().off('click.popmake');
                })
                .popmake('open');
        }


        if (ajax_login !== undefined || ajax_registration !== undefined || ajax_recovery !== undefined) {


            $this.on('popmakeBeforeOpen', function () {

                var trigger = $($.fn.popmake.last_open_trigger),
                    popswitch = null;

                if (trigger.length) {

                    if (trigger.hasClass('popswitch-registration') || trigger.find('.popswitch-registration').length) {
                        login.hide();
                        registration.show();
                        recovery.hide();
                        popswitch = true;
                    }
                    if (trigger.hasClass('popswitch-recovery') || trigger.find('.popswitch-recovery').length) {
                        login.hide();
                        registration.hide();
                        recovery.show();
                        popswitch = true;
                    }
                    if (trigger.hasClass('popswitch-login') || trigger.find('.popswitch-login').length || popswitch === null) {
                        login.show();
                        registration.hide();
                        recovery.hide();
                    }

                }
            });

        }

        $('.popmake-' + settings.id + '.popswitch-login', $this).off('click').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            registration.slideUp();
            recovery.slideUp();
            login.appendTo(login.parent()).slideDown();
        });
        $('.popmake-' + settings.id + '.popswitch-registration', $this).off('click').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            login.slideUp();
            recovery.slideUp();
            registration.appendTo(registration.parent()).slideDown();
        });
        $('.popmake-' + settings.id + '.popswitch-recovery', $this).off('click').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            login.slideUp();
            registration.slideUp();
            recovery.appendTo(recovery.parent()).slideDown();
        });

    });

}(jQuery));