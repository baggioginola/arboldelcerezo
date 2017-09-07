/**
 * Created by mario.cuevas on 5/12/2016.
 */
$(document).ready(function () {

    $('#reset_button').click(function () {
        $('#form_global').trigger("reset");
        $('#submit_type').val('usuarios/add');
        $('#submit_id').val('');
        return false;
    });

    var url = 'usuarios/getAll';
    var columns = [{data: 'nombre'}, {data: 'apellidos'}, {data: 'email'}];

    var table = masterDatatable(url, columns);

    $('#datatable tbody').on('click', '#btn_edit', function () {

        var id = table.row($(this).parents('tr')).data().id;

        var data = {id: id};
        var url = 'usuarios/getById';

        $('#submit_type').val('usuarios/edit');
        $('#id_password').val('');

        $.post(url, data, function (response, status) {
            if (status == 'success') {
                $.each(response, function (key, val) {
                    $("input[name=" + key + "]").val(val);
                    $("select[name=" + key + "]").val(val);
                });
            }
            $('#id_password').val(response.password);
            $('#submit_pw').val(response.password);
            $('#submit_id').val(response.id);
        }, 'json');
        return false;
    });

    $('#datatable tbody').on('click', '#btn_delete', function () {
        var id = table.row($(this).parents('tr')).data().id_usuario;
        bootbox.confirm("Eliminar elemento?", function (result) {
            if (result == true) {
                var data = {id_usuario: id, status: 0};
                var url = 'usuarios/delete';
                $.post(url, data, function (response, status) {
                    if (status == 'success') {
                        bootbox.alert(response.message);
                        table.ajax.reload();
                    }
                }, 'json');
            }
        });
        return false;
    });

    var form = $('#form_global').submit(function () {
        if ($('#id_submit').hasClass('disabled')) {
            return false;
        }

        var pw = $('#id_password').val();
        if (pw != $('#submit_pw').val()) {
            pw = hex_md5(pw);
        }

        var data = $(this).serialize() + '&' + $.param({'password': pw});
        var type = $('#submit_type').val();
        if (type == 'usuarios/edit') {
            var id = $('#submit_id').val();
            data = data + '&' + $.param({'id_usuario': id});
        }

        $.ajax({
            url: type,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (data) {
                table.ajax.reload();
                submit_response_general(form, data, 'usuarios/add');
            }
        });
        return false;
    });
});