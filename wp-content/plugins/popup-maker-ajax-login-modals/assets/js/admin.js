(function () {
    "use strict";
    jQuery(document).ready(function () {
        if (jQuery('body.post-type-popup form#post').length) {
            var login_redirect_enabled_check = function () {
                    if (!jQuery("#popup_ajax_login_disable_redirect").is(':checked')) {
                        jQuery('.ajax_login_redirect_enabled').show();
                    } else {
                        jQuery('.ajax_login_redirect_enabled').hide();
                    }
                },
                registration_redirect_enabled_check = function () {
                    if (!jQuery("#popup_ajax_registration_disable_redirect").is(':checked')) {
                        jQuery('.ajax_registration_redirect_enabled').show();
                    } else {
                        jQuery('.ajax_registration_redirect_enabled').hide();
                    }
                },
                recovery_redirect_enabled_check = function () {
                    if (!jQuery("#popup_ajax_recovery_disable_redirect").is(':checked')) {
                        jQuery('.ajax_recovery_redirect_enabled').show();
                    } else {
                        jQuery('.ajax_recovery_redirect_enabled').hide();
                    }
                },
                login_enabled_check = function () {
                    if (jQuery("#popup_ajax_login_enabled").is(':checked')) {
                        jQuery('.ajax_login_enabled').show();
                        login_redirect_enabled_check();
                    } else {
                        jQuery('.ajax_login_enabled').hide();
                    }
                },
                registration_enabled_check = function () {
                    if (jQuery("#popup_ajax_registration_enabled").is(':checked')) {
                        jQuery('.ajax_registration_enabled').show();
                        registration_redirect_enabled_check();
                    } else {
                        jQuery('.ajax_registration_enabled').hide();
                    }
                },
                recovery_enabled_check = function () {
                    if (jQuery("#popup_ajax_recovery_enabled").is(':checked')) {
                        jQuery('.ajax_recovery_enabled').show();
                        recovery_redirect_enabled_check();
                    } else {
                        jQuery('.ajax_recovery_enabled').hide();
                    }
                };

            jQuery(document)
                .on('click', "#popup_ajax_login_enabled", login_enabled_check)
                .on('click', "#popup_ajax_registration_enabled", registration_enabled_check)
                .on('click', "#popup_ajax_recovery_enabled", recovery_enabled_check)
                .on('click', "#popup_ajax_login_disable_redirect", login_redirect_enabled_check)
                .on('click', "#popup_ajax_registration_disable_redirect", registration_redirect_enabled_check)
                .on('click', "#popup_ajax_recovery_disable_redirect", recovery_redirect_enabled_check);

            login_enabled_check();
            registration_enabled_check();
            recovery_enabled_check();
        }
    });
}());