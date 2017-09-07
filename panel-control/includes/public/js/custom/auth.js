/**
 * Created by mario.cuevas on 9/22/2016.
 */

$(document).ready(function(){
    $('#auth').submit(function(){
        var url = 'login/authenticate';

        var pw = $('#id_password').val();
        pw = hex_md5('3lem3nt' + pw);

        var data = $(this).serialize() + '&' + $.param({'password': pw});

        $.ajax({
            url: url,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (data) {
                if(data.status ==200) {
                    location.href = BASE_ROOT + 'categorias';
                }
            }
        });

        return false;
    });
});