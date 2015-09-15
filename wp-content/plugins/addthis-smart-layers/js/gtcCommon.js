(function($, window, document, undefined) {
  $(function() {
    window.commonMethods = {

        localStorageSettings: function(obj, callback) {

            var tempObj = {};

            if(!obj.namespace || !obj.namespace.length || !obj.method || !obj.method.length) return;

            if(window.localStorage && window.JSON) {

                if(obj.method.toLowerCase() === "get") {

                    callback.call(this, JSON.parse(window.localStorage.getItem(obj.namespace)));

                }

                else if(obj.method.toLowerCase() === "set" && obj.data != null && $.isPlainObject(obj.data)) {

                    tempObj = $.extend({}, JSON.parse(window.localStorage.getItem(obj.namespace)), obj.data);

                    return window.localStorage.setItem(obj.namespace, JSON.stringify(tempObj));

                }

                else if(obj.method.toLowerCase() === "set" && obj.data != null && $.isArray(obj.data)) {

                    return window.localStorage.setItem(obj.namespace, JSON.stringify(obj.data));


                }

                else if(obj.method.toLowerCase() === "remove") {

                    return window.localStorage.removeItem(obj.namespace);

                }

            }

        },

        resetOptions: function(namespace, obj, callback) {

            if(obj) {

                for(var x in obj) {

                    if($(x).is(':checkbox') || $(x).is(':radio')) $(x).prop('checked', obj[x]).change();

                    else $(x).val(obj[x]).change().keyup();

                }

                if(window.localStorage && namespace) {

                    commonMethods.localStorageSettings({ namespace: namespace, method: "remove" });

                }

                if(callback) callback.call(this);

            }

        },

        loadCode: function (namespace, callback) {

            commonMethods.localStorageSettings({ namespace: namespace, method: "get" }, function(obj) {

                if(obj) {

                    for(var x in obj) {

                        if($(x).is(':checkbox') || $(x).is(':radio')) {

                            $(x).prop('checked', obj[x]).val(obj[x]);

                            if($(x).is(':checked')) $(x).trigger('auto-dismiss');

                        }

                        else $(x).val(obj[x]).attr("data-updated", "updated");

                    }

                }

                if(callback) callback.call(this, obj);

            });

        },
        tabularBro: function() {
            $('.addthis-tabs a').on('click', function(ev) {
                var tabs = $('.addthis-tabs'),
                    listItems = tabs.find('li'),
                    currentListItem = $(this).parent('li'),
                    content = $(this).attr('data-content'),
                    currentContent = $('#' + content);
                    tabsOnPage = $('.addthis-tab'),
                    href = $(this).attr('href');
                if(content) {
                    listItems.removeClass('active');
                    currentListItem.addClass('active');
                    tabsOnPage.removeClass('active');
                    currentContent.addClass('active');
                }
                if(href === '#') {
                  ev.preventDefault();
                }
            });
        }
    };

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $('.at-vertical-menu').attr('style','display: block !important');
            $('.at4-follow').attr('style','display: block !important');
        }
    });


    window.addthisnamespaces = {
        sl40: 'addthis-smartlayers'
    };
    //Calls the selectBoxIt method on your HTML select box
    var selectBox = $("select").not("#serviceSelect").selectBoxIt({ autoWidth: false });

    commonMethods.tabularBro();
  });
}(jQuery, window, document));