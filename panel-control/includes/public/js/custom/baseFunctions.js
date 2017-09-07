/**
 * Created by mario.cuevas on 6/16/2016.
 */
/**
 *
 * @param form
 * @param data
 */
function submit_response(form, data, url, type) {
    $('#id_submit').addClass('disabled');
    $('#submit_pw').val('');
    $('#submit_type').val(url);
    $('#submit_id').val('');

    form.trigger("reset");
    bootbox.alert(data.message);
    $('#upload_images').val('1');


    var image = $('#id_imagen');

    image.fileinput("destroy");

    image.fileinput({
        uploadUrl: "imagenes/add",
        allowedFileExtensions: ["jpg", "png", "jpeg"],
        maxFileCount: 10,
        minFileCount: 1,
        uploadAsync: false,
        language: "es",
        showUpload: false,
        fileActionSettings: {showUpload: false, showZoom: false},
        previewSettings: {image: {width: "auto", height: "100px"}},
        //overwriteInitial: true,
        purifyHtml: true,
        autoReplace: true,
        uploadExtraData: function (previewId, index) {
            var info = {
                "type": type,
                "name": $('#submit_id').val(),
                'num_imagenes': $('.file-initial-thumbs > div').length + $('.file-live-thumbs > div').length
            };
            return info;
        }
    }).on('filebatchuploadsuccess', function (event, data) {
        var out = '';
    }).on('fileloaded', function (event, file, previewId, index, reader) {
        $('#upload_images').val('1');
    });
    image.fileinput("enable");
}


function submit_response_general(form, data, url) {
    $('#id_submit').addClass('disabled');
    $('#submit_pw').val('');
    $('#submit_type').val(url);
    $('#submit_id').val('');

    form.trigger("reset");
    bootbox.alert(data.message);
}
/**
 *
 * @param url
 * @param columns
 * @returns {*|jQuery}
 */
function masterDatatable(url, columns) {
    var defaultContentEdit = "<button class='btn btn-primary btn-xs' id='btn_edit'><span class='glyphicon glyphicon-pencil'></span></button>";
    var defaultContentDelete = "<button class='btn btn-danger btn-xs' id='btn_delete' ><span class='glyphicon glyphicon-trash'></span></button>"

    var columnsDefault = [{"orderable": false, "data": null, "defaultContent": defaultContentEdit},
        {
            "orderable": false, "data": null, "defaultContent": defaultContentDelete
        }];

    columns = columns.concat(columnsDefault);

    var dataTable = $('#datatable').DataTable({
        ajax: {
            type: 'POST',
            url: url,
            dataSrc: ''
        },
        columns: columns,
        /*select: {
         style: 'os'
         },
         */
        language: {
            "lengthMenu": "Mostrando _MENU_ elementos por pagina",
            "zeroRecords": "No se ha encontrado",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos disponibles",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
        "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
    });
    return (dataTable);
}

function masterDatatableCustomers(url, columns) {
    var defaultContentEdit = "<button class='btn btn-primary btn-xs' id='btn_edit'><span class='glyphicon glyphicon-pencil'></span></button>";

    var columnsDefault = [{"orderable": false, "data": null, "defaultContent": defaultContentEdit}];

    columns = columns.concat(columnsDefault);

    var dataTable = $('#datatable').DataTable({
        ajax: {
            type: 'POST',
            url: url,
            dataSrc: ''
        },
        columns: columns,
        /*select: {
         style: 'os'
         },
         */
        language: {
            "lengthMenu": "Mostrando _MENU_ elementos por pagina",
            "zeroRecords": "No se ha encontrado",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos disponibles",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
    return (dataTable);
}

function getImage(root_images, name, i) {
    var extension = '.jpg';
    var url = '';
    var return_name = '';

    if (i == 1) {
        url = root_images + name;
        return_name = name;
    }
    else {
        url = root_images + name + '_' + i;
        return_name = name + '_' + i;
    }


    var exists = $.ajax({
        url: url + extension,
        type: "POST",
        cache: false,
        dataType: 'json',
        async: false
    });

    if (exists.status != 200) {
        extension = '.png';
        var exists_png = $.ajax({
            url: url + extension,
            type: "POST",
            cache: false,
            dataType: 'json',
            async: false
        });
        if (exists_png.status != 200) {
            return {
                status: 404
            };
        }
        return {
            status: 200,
            url: url + extension,
            extension: extension,
            name: return_name + extension
        };
    }
    return {
        status: 200,
        url: url + extension,
        extension: extension,
        name: return_name + extension
    };

}