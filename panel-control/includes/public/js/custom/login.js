/**
 * Created by mario on 26/ene/2017.
 */
jQuery(document).ready(function () {
    var form = $('#form_login').submit(function () {
        var url = BASE_ROOT + 'login/authenticate';
        var salt = '4rb01';
        var pw = jQuery('#id_password').val();
        pw = hex_md5(salt + pw);

        var data = jQuery(this).serialize() + '&' + jQuery.param({'password': pw});

        $.ajax({
            url: url,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.status == 200) {
                    location.href = BASE_ROOT;
                }
                else {
                    bootbox.alert('Usuario y/o password incorrectos, intente de nuevo');
                }
            }
        });
        return false;
    });
});