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

    loadPackages();
}

function loadPackages() {
    $.ajax({
        url: '/admin/packages',
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
        var route = `/admin/packages/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                package = result.data;
                fillPackageForm(package);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Paquete.',
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
            text: `Se eliminará el paquete ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/packages/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadPackages();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el paquete correctamente.',
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

function fillPackageForm(data) {
    $("#id").val(data.id);
    $("#name").val(data.name);
    $("#workshop_event_1_id").val(data.workshop_event_1_id);
    $("#adoption_event_1_id").val(data.adoption_event_1_id);
    $("#workshop_event_2_id").val(data.workshop_event_2_id);
    $("#adoption_event_2_id").val(data.adoption_event_2_id);
    $("#workshop_event_3_id").val(data.workshop_event_3_id);
    $("#adoption_event_3_id").val(data.adoption_event_3_id);
    $("#workshop_event_4_id").val(data.workshop_event_4_id);
    $("#adoption_event_4_id").val(data.adoption_event_4_id);
    $("#description").val(data.description);
}

function cleanPackageForm() {
    $("#id").val('');
    $("#name").val('');
    $("#workshop_event_1_id").val('');
    $("#adoption_event_1_id").val('');
    $("#workshop_event_2_id").val('');
    $("#adoption_event_2_id").val('');
    $("#workshop_event_3_id").val('');
    $("#adoption_event_3_id").val('');
    $("#workshop_event_4_id").val('');
    $("#adoption_event_4_id").val('');
    $("#description").val('');
}

function savePackage() {
    var error = false;
    var id = $("#id").val();
    var name = $("#name").val();
    var workshop_event_1_id = $("#workshop_event_1_id").val();
    var adoption_event_1_id = $("#adoption_event_1_id").val();
    var workshop_event_2_id = $("#workshop_event_2_id").val();
    var adoption_event_2_id = $("#adoption_event_2_id").val();
    var workshop_event_3_id = $("#workshop_event_3_id").val();
    var adoption_event_3_id = $("#adoption_event_3_id").val();
    var workshop_event_4_id = $("#workshop_event_4_id").val();
    var adoption_event_4_id = $("#adoption_event_4_id").val();
    var description = $("#description").val();

    !name.trim() && (error = true) && toastr.warning('El nombre es requerido.', 'Información incompleta!');
    !workshop_event_1_id && (error = true) && toastr.warning('El evento es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'name': name,
        'workshop_event_1_id': workshop_event_1_id,
        'adoption_event_1_id': adoption_event_1_id,
        'workshop_event_2_id': workshop_event_2_id,
        'adoption_event_2_id': adoption_event_2_id,
        'workshop_event_3_id': workshop_event_3_id,
        'adoption_event_3_id': adoption_event_3_id,
        'workshop_event_4_id': workshop_event_4_id,
        'adoption_event_4_id': adoption_event_4_id,
        'description': description
    }

    if(id) {
        data['id'] = id;
        sendUpsertPackageRequest(`/admin/packages/${id}`, 'put', data);
    } else {
        sendUpsertPackageRequest('/admin/packages', 'post', data);
    }
}

function sendUpsertPackageRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanPackageForm();
            $("#addModal").modal('hide');
            loadPackages();

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

