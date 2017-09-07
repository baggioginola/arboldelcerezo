/**
 * Created by mario.cuevas on 5/12/2016.
 */
$(document).ready(function () {

    $("#id_imagen").fileinput({
        uploadUrl: "imagenes/add",
        allowedFileExtensions: ["jpg", "png", "jpeg"],
        maxFileCount: 1,
        minFileCount: 1,
        uploadAsync: false,
        language: "es",
        showUpload: false,
        fileActionSettings: {showUpload: false, showZoom: false},
        previewSettings: {image: {width: "auto", height: "100px"}},
        purifyHtml: true,
        autoReplace: true,
        uploadExtraData: function (previewId, index) {
            var info = {
                "type": "categorias",
                "name": $('#submit_id').val(),
                'num_imagenes': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
            };
            return info;
        }
    }).on('filebatchuploadsuccess', function (event, data) {
        bootbox.alert('Las imágenes se han subido correctamente');
    }).on('fileloaded', function (event, file, previewId, index, reader) {
        $('#upload_images').val('1');
    });

    $('#reset_button').click(function () {
        $('#form_global').trigger("reset");
        $('#submit_type').val('categorias/add');
        $('#submit_id').val('');

        return false;
    });

    var url = 'categorias/getAll';
    var columns = [{data: 'tipo'}, {data: 'nombre'}];
    var table = masterDatatable(url, columns);

    var url_last_id = 'categorias/getLastId';

    $.ajax({
        url: url_last_id,
        type: "POST",
        cache: false,
        data: {},
        dataType: 'json',
        success: function (data) {
            $('#submit_id').val(parseInt(data.id) + 1);
        }
    });

    $('#datatable tbody').on('click', '#btn_edit', function () {

        $("#form_alert").slideUp();
        var id = table.row($(this).parents('tr')).data().id_categoria;

        var data = {id_categoria: id};
        var url = 'categorias/getById';

        $('#submit_type').val('categorias/edit');

        $.post(url, data, function (response, status) {
            if (status == 'success') {
                $.each(response, function (key, val) {
                    $("input[name=" + key + "]").val(val);
                    $("textarea[name=" + key + "]").val(val);
                    $("select[name=" + key + "]").val(val);
                });

                var images = [];
                var initialPreviewConfigObj = [];
                var j = 0;
                var i = 1;

                var dataImage = getImage(IMAGES_CATEGORIES, response.id_categoria, i);
                if (dataImage.status == 200) {
                    images[j] = '<img src="' + dataImage.url + '" class="file-preview-image" alt="Desert" title="Desert" style="width:auto; height:100px;">';

                    var initialPreviewConfigItem = {};
                    initialPreviewConfigItem['caption'] = dataImage.name;
                    initialPreviewConfigItem['key'] = j;
                    initialPreviewConfigObj.push(initialPreviewConfigItem);
                    j++;
                }

                $('#id_imagen').fileinput('refresh', {
                    uploadUrl: "imagenes/edit",
                    allowedFileExtensions: ["jpg", "png", "jpeg"],
                    initialPreview: images,
                    initialPreviewFileType: 'image',
                    initialPreviewShowDelete: false,
                    initialPreviewConfig: initialPreviewConfigObj,
                    validateInitialCount: true,
                    fileActionSettings: {showDrag: false},
                    append: true,
                    showUploadedThumbs: false,
                    uploadExtraData: function (previewId, index) {
                        var info = {
                            "type": "categorias",
                            "name": $("#submit_id").val(),
                            'num_imagenes': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
                        };
                        return info;
                    }
                });

                $('#upload_images').val('0');
            }
            $('#submit_id').val(response.id_categoria);
        }, 'json');
        return false;
    });

    $('#datatable tbody').on('click', '#btn_delete', function () {
        var id = table.row($(this).parents('tr')).data().id_categoria;
        bootbox.confirm("Eliminar elemento?", function (result) {
            if (result == true) {
                var data = {id_categoria: id, status: 0};
                var url = 'categorias/delete';
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

        var type = $('#submit_type').val();

        if ($('#id_imagen').fileinput('upload') == null && $('#upload_images').val() == 1) {
            return false;
        }

        var live_count = $('.file-initial-thumbs > div').length;
        var initial_count = $('.file-live-thumbs > div').length;

        var fileStack = live_count + initial_count;

        var data = $(this).serialize();

        if (type == 'categorias/edit') {
            var id = $('#submit_id').val();
            data = data + '&' + $.param({'id_categoria': id});
        }

        $.ajax({
            url: type,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            async: false,
            success: function (data) {
                table.ajax.reload();
                submit_response(form, data, 'categorias/add', 'categorias');
            }
        });
        return false;
    });
});