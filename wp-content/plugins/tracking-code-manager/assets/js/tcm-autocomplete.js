jQuery(function() {
    var url = TCMAutocomplete.url + "?action=tcm_search";
    /*
    jQuery("[name=optionPosts]").autocomplete({
        source: url
        , delay: 200
        , minLength: 3
    });
    */

    //var options={multiple:true, multipleSep: ","};
    //jQuery("[name=optionPosts]").suggest(url, options);
    jQuery(".tcm-hideShow").click(function() {
        tcm_hideShow(this);
    });
    jQuery(".tcm-hideShow").change(function() {
        tcm_hideShow(this);
    });
    jQuery(".tcm-hideShow").each(function() {
        tcm_hideShow(this);
    });

    //mostra o nasconde un div collegato ad una checkbox
    function tcm_hideShow(v) {
        var $source=jQuery(v);
        if($source.attr('tcm-hideIfTrue') && $source.attr('tcm-hideShow')) {
            var $destination=jQuery('[name='+$source.attr('tcm-hideShow')+']');
            if($destination.length==0) {
                $destination=jQuery('#'+$source.attr('tcm-hideShow'));
            }
            if($destination.length>0) {
                if($source.is(':radio')) {
                    //mmmh
                }
                var isChecked=$source.is(":checked");
                var hideIfTrue=($source.attr('tcm-hideIfTrue').toLowerCase()=='true');

                if(isChecked) {
                    if(hideIfTrue) {
                        $destination.hide();
                    } else {
                        $destination.show();
                    }
                } else {
                    if(hideIfTrue) {
                        $destination.show();
                    } else {
                        $destination.hide();
                    }
                }
            }
        }
    }
});