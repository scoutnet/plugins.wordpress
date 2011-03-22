if (jQuery) {

    bindAutocomp = function() {
        jQuery('.snk_ssid_chooser').autocomplete('http://pfadis.com/app/api/rest/Unit?rpp=20&short=true', {
            dataType: "jsonp",
            minChars : 4,
            width: 275,
            delay: 250,
            formatItem: function(item) {
                if (item.distance) {
                    return item.fullname + ' (ca. ' + item.distance + ' km)';
                }
                return item.fullname;
            },
            parse: function(data) {
                return jQuery.map(data.result, function(row) {
                    return {
                        data: row,
                        value: row.id,
                        result: row.id
                    }
                });
            }
        });
    }



    jQuery(document).ready(function() {

        // hide description
        jQuery('.snk_widget .snk_description').hide();
        jQuery('.snk_widget .snk_author').hide();
        jQuery('.snk_widget .snk_title').css('cursor', 'pointer');

        jQuery('.snk_widget .snk_event').click(function() {
            jQuery('.snk_description', this).toggle();
        });

        bindAutocomp();

    });
}