/**
 * Created by mario on 25/ene/2017.
 */
jQuery(document).ready(function () {

    jQuery('#reset_button').click(function () {
        jQuery('#form_contact').trigger("reset");

        return false;
    });

    var form = jQuery('#form_contact').submit(function () {
        var data = jQuery(this).serialize();
        jQuery.ajax({
            url: BASE_ROOT + 'contact/sendMessage',
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            async: false,
            success: function (data) {
                form.trigger("reset");
                bootbox.alert('Gracias por contactarnos');
            }
        });
        return false;
    });
});