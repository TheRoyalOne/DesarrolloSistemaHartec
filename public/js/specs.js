function initializeTable() {
    $('#table').bootstrapTable({
        formatLoadingMessage: function() {
            return 'Cargando, por favor espere...';
        },
        formatNoMatches: function() {
            return 'No se encontraron registros.'
        },
        formatRecordsPerPage: function (pageNumber) {
            return `${pageNumber} registros por pagina`;
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return `Mostrando filas ${pageFrom} a ${pageTo} de ${totalRows}`;
        }
    });

    loadSpecs();
}

function loadSpecs() {
    $.ajax({
        url: '/admin/specs',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            $('#table').bootstrapTable('load', result.data);
        },
        error: function(err) {
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}

function editColumnFormatter(value, row, index, field) {
    return [
        '<button class="btn btn-warning btn-edit" ><i class="fa fa-pencil"></i> </button>'
    ].join('');
}

function deleteColumnFormatter(value, row, index, field) {
    return [
        '<button class="btn btn-danger btn-delete"><i class="fa fa-trash"></i> </button>'
    ].join('');
}

var editColumnEvent = {
    'click .btn-edit': function(e, value, row, index)
    {
        var route = `/admin/specs/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                spec = result.data;
                fillSpecForm(spec);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Especificación.',
                    text: `${err.error.user_message}`,
                    icon: 'error'
                });
            }
        });
    }
}

var deleteColumnEvent = {
    'click .btn-delete': function(e, value, row, index) {
        Swal.fire({
            allowOutsideClick: true,
            title: '¿Esta seguro?',
            text: `Se eliminará la especificación ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/specs/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadSpecs();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la especificación correctamente.',
                            icon: 'success'
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            allowOutsideClick: true,
                            title: 'Error al eliminar.',
                            text: `${err.error.user_message}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
}

function fillSpecForm(data) {
    $("#id").val(data.id);
    $("#name").val(data.name);
    $("#spec_frut1").val(data.spec_frut1);
    $("#spec_frut2").val(data.spec_frut2);
    $("#spec_frut3").val(data.spec_frut3);

    $("#spec_orn1").val(data.spec_orn1);
    $("#spec_orn2").val(data.spec_orn2);
    $("#spec_orn3").val(data.spec_orn3);

    $("#spec_conymad1").val(data.spec_conymad1);
    $("#spec_conymad2").val(data.spec_conymad2);
    $("#spec_conymad3").val(data.spec_conymad3);

    $("#spec_hojacad1").val(data.spec_hojacad1);
    $("#spec_hojacad2").val(data.spec_hojacad2);
    $("#spec_hojacad3").val(data.spec_hojacad3);

    $("#spec_banq1").val(data.spec_banq1);
    $("#spec_banq2").val(data.spec_banq2);
    $("#spec_banq3").val(data.spec_banq3);

    $("#spec_llan1").val(data.spec_llan1);
    $("#spec_llan2").val(data.spec_llan2);
    $("#spec_llan3").val(data.spec_llan3);

    $("#spec_mac1").val(data.spec_mac1);
    $("#spec_mac2").val(data.spec_mac2);
    $("#spec_mac3").val(data.spec_mac3);

    $("#spec_azotea1").val(data.spec_azotea1);
    $("#spec_azotea2").val(data.spec_azotea2);
    $("#spec_azotea3").val(data.spec_azotea3);

    $("#spec_int1").val(data.spec_int1);
    $("#spec_int2").val(data.spec_int2);
    $("#spec_int3").val(data.spec_int3);

    $("#spec_ext1").val(data.spec_ext1);
    $("#spec_ext2").val(data.spec_ext2);
    $("#spec_ext3").val(data.spec_ext3);

    $("#spec_plant1").val(data.spec_plant1);
    $("#spec_plant2").val(data.spec_plant2);
    $("#spec_plant3").val(data.spec_plant3);

    $("#spec_suc1").val(data.spec_suc1);
    $("#spec_suc2").val(data.spec_suc2);
    $("#spec_suc3").val(data.spec_suc3);
}

function cleanSpecForm() {
    $("#id").val('');
    $("#name").val('');
    $("#spec_frut1").val('');
    $("#spec_frut2").val('');
    $("#spec_frut3").val('');

    $("#spec_orn1").val('');
    $("#spec_orn2").val('');
    $("#spec_orn3").val('');

    $("#spec_conymad1").val('');
    $("#spec_conymad2").val('');
    $("#spec_conymad3").val('');

    $("#spec_hojacad1").val('');
    $("#spec_hojacad2").val('');
    $("#spec_hojacad3").val('');

    $("#spec_banq1").val('');
    $("#spec_banq2").val('');
    $("#spec_banq3").val('');

    $("#spec_llan1").val('');
    $("#spec_llan2").val('');
    $("#spec_llan3").val('');

    $("#spec_mac1").val('');
    $("#spec_mac2").val('');
    $("#spec_mac3").val('');

    $("#spec_azotea1").val('');
    $("#spec_azotea2").val('');
    $("#spec_azotea3").val('');

    $("#spec_int1").val('');
    $("#spec_int2").val('');
    $("#spec_int3").val('');

    $("#spec_ext1").val('');
    $("#spec_ext2").val('');
    $("#spec_ext3").val('');

    $("#spec_plant1").val('');
    $("#spec_plant2").val('');
    $("#spec_plant3").val('');

    $("#spec_suc1").val('');
    $("#spec_suc2").val('');
    $("#spec_suc3").val('');
}

function saveSpec()
{
    var error = false;
    var id = $("#id").val();

    var name = $("#name").val();
    var spec_frut1 = $("#spec_frut1").val();
    var spec_frut2 = $("#spec_frut2").val();
    var spec_frut3 = $("#spec_frut3").val();

    var spec_orn1 = $("#spec_orn1").val();
    var spec_orn2 = $("#spec_orn2").val();
    var spec_orn3 = $("#spec_orn3").val();

    var spec_conymad1 = $("#spec_conymad1").val();
    var spec_conymad2 = $("#spec_conymad2").val();
    var spec_conymad3 = $("#spec_conymad3").val();

    var spec_hojacad1 = $("#spec_hojacad1").val();
    var spec_hojacad2 = $("#spec_hojacad2").val();
    var spec_hojacad3 = $("#spec_hojacad3").val();

    var spec_banq1 = $("#spec_banq1").val();
    var spec_banq2 = $("#spec_banq2").val();
    var spec_banq3 = $("#spec_banq3").val();

    var spec_llan1 = $("#spec_llan1").val();
    var spec_llan2 = $("#spec_llan2").val();
    var spec_llan3 = $("#spec_llan3").val();

    var spec_mac1 = $("#spec_mac1").val();
    var spec_mac2 = $("#spec_mac2").val();
    var spec_mac3 = $("#spec_mac3").val();

    var spec_azotea1 = $("#spec_azotea1").val();
    var spec_azotea2 = $("#spec_azotea2").val();
    var spec_azotea3 = $("#spec_azotea3").val();

    var spec_int1 = $("#spec_int1").val();
    var spec_int2 = $("#spec_int2").val();
    var spec_int3 = $("#spec_int3").val();

    var spec_ext1 = $("#spec_ext1").val();
    var spec_ext2 = $("#spec_ext2").val();
    var spec_ext3 = $("#spec_ext3").val();

    var spec_plant1 = $("#spec_plant1").val();
    var spec_plant2 = $("#spec_plant2").val();
    var spec_plant3 = $("#spec_plant3").val();

    var spec_suc1 = $("#spec_suc1").val();
    var spec_suc2 = $("#spec_suc2").val();
    var spec_suc3 = $("#spec_suc3").val();

    !name.trim() && (error = true) && toastr.warning('El Nombre de la Especificación es requerido', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'name': name,
        'spec_frut1': spec_frut1,
        'spec_frut2': spec_frut2,
        'spec_frut3': spec_frut3,
        'spec_orn1': spec_orn1,
        'spec_orn2': spec_orn2,
        'spec_orn3': spec_orn3,
        'spec_conymad1': spec_conymad1,
        'spec_conymad2': spec_conymad2,
        'spec_conymad3': spec_conymad3,
        'spec_hojacad1': spec_hojacad1,
        'spec_hojacad2': spec_hojacad2,
        'spec_hojacad3': spec_hojacad3,
        'spec_llan1': spec_llan1,
        'spec_llan2': spec_llan2,
        'spec_llan3': spec_llan3,
        'spec_banq1': spec_banq1,
        'spec_banq2': spec_banq2,
        'spec_banq3': spec_banq3,
        'spec_mac1': spec_mac1,
        'spec_mac2': spec_mac2,
        'spec_mac3': spec_mac3,
        'spec_azotea1': spec_azotea1,
        'spec_azotea2': spec_azotea2,
        'spec_azotea3': spec_azotea3,
        'spec_int1': spec_int1,
        'spec_int2': spec_int2,
        'spec_int3': spec_int3,
        'spec_ext1': spec_ext1,
        'spec_ext2': spec_ext2,
        'spec_ext3': spec_ext3,
        'spec_plant1': spec_plant1,
        'spec_plant2': spec_plant2,
        'spec_plant3': spec_plant3,
        'spec_suc1': spec_suc1,
        'spec_suc2': spec_suc2,
        'spec_suc3': spec_suc3
    };

    if(id) {
        data['id'] = id;
        sendUpsertSpecRequest(`/admin/specs/${id}`, 'put', data);
    } else {
        sendUpsertSpecRequest('/admin/specs', 'post', data);
    }
}

function sendUpsertSpecRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanSpecForm();
            $("#addModal").modal('hide');
            loadSpecs();

            Swal.fire({
                title: result.data.name,
                text: 'Se actualizó correctamente.',
                icon: 'success',
            });
        },
        error: function(err) {
            console.log('error: ', err);
            err_resp = err.responseJSON;

            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al guardar.',
                text: err_resp.user_message,
                icon: 'error'
            });
        }
    });
}
