/**
 * Created by mario.cuevas on 7/6/2016.
 */
$(document).ready(function () {
    tinymce.init({
        selector: "textarea#id_descripcion",
        menubar: "edit",

        theme: "modern",
        toolbar: " undo redo |  bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
        plugins: [
            "advlist autolink link image lists charmap hr anchor pagebreak spellchecker",
            "searchreplace visualblocks visualchars  fullscreen insertdatetime  nonbreaking",
            "save table contextmenu directionality template paste "
        ]

    });
    $('#id_fecha').datepicker();

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
                "name": $('#submit_id').val()
            };
            return info;
        }
    }).on('filebatchuploadsuccess', function (event, data) {
        bootbox.alert('Las imágenes se han subido correctamente');
    }).on('fileloaded', function (event, file, previewId, index, reader) {
        $('#upload_images').val('1');
    });

    $('#reset_button').click(function () {
        $("#id_imagen").fileinput("refresh");
        $('#form_global').trigger("reset");
        $('#submit_type').val('productos/add');

        return false;
    });

    $.post('categorias/getAll', function (response) {
        $.each(response, function (key, val) {
            $("#id_categoria").append('<option value="' + val.id + '">' + val.nombre + '</option>');
        });
    }, 'json');

    var url = 'productos/getAll';
    var columns = [{data: 'categoria'}, {data: 'nombre'}, {data: 'precio'}];

    var table = masterDatatable(url, columns);

    var url_last_id = 'productos/getLastId';

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
        var id = table.row($(this).parents('tr')).data().id;

        var data = {id: id};
        var url = 'productos/getById';

        $('#submit_type').val('productos/edit');

        $.post(url, data, function (response, status) {
            if (status == 'success') {
                $.each(response, function (key, val) {
                    if (key == 'descripcion') {
                        tinyMCE.get('id_descripcion').setContent(val);
                    }
                    $("textarea[name=" + key + "]").val(val);
                    $("input[name=" + key + "]").val(val);
                    $("select[name=" + key + "]").val(val);
                });
                var images = [];
                var initialPreviewConfigObj = [];
                var j = 0;
                var num_images = 1;
                for (var i = 1; i <= num_images; i++) {
                    var dataImage = getImage(IMAGES_PRODUCTS, response.id, i);
                    if (dataImage.status == 200) {
                        images[j] = '<img src="' + dataImage.url + '" class="file-preview-image" alt="Desert" title="Desert" style="width:auto; height:100px;">';

                        var initialPreviewConfigItem = {};
                        initialPreviewConfigItem['caption'] = dataImage.name;
                        initialPreviewConfigItem['key'] = j;
                        initialPreviewConfigObj.push(initialPreviewConfigItem);
                        j++;
                    }
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
                            "name": $("#submit_id").val()
                        };
                        return info;
                    }
                });

                $('#upload_images').val('0');
            }
            $('#submit_id').val(response.id);
        }, 'json');
        return false;
    });

    $('#datatable tbody').on('click', '#btn_delete', function () {
        var id = table.row($(this).parents('tr')).data().id;
        bootbox.confirm("Eliminar elemento?", function (result) {
            if (result == true) {
                var data = {id: id, status: 0};
                var url = 'productos/delete';

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

        var data = $(this).serialize();

        if (type == 'productos/edit') {
            var id = $('#submit_id').val();
            data = data + '&' + $.param({'id': id});
        }

        data = data + '&' + $.param({'descripcion': tinyMCE.get('id_descripcion').getContent()});

        $.ajax({
            url: type,
            type: "POST",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
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
                    table.ajax.reload();
                    submit_response(form, data, 'productos/add', 'productos');
                }
                else {
                    bootbox.alert(data.message);
                }
            }
        })
        ;
        return false;
    });
});