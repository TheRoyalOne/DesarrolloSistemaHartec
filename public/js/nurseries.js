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

    loadNurseries();
}

function loadNurseries() {
    $.ajax({
        url: '/admin/nurseries',
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
        var route = `/admin/nurseries/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                nursery = result.data;
                fillNurseryForm(nursery);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Vivero.',
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
            text: `Se eliminará el vivero ${row.question}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/nurseries/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadNurseries();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el vivero correctamente.',
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

function fillNurseryForm(data) {
    $("#id").val(data.id);
    $("#name").val(data.name);
    $("#responsable_user_id").val(data.responsable_user_id);
    $("#ubication").val(data.ubication);
}

function cleanNurseryForm() {
    $("#id").val('');
    $("#name").val('');
    $("#responsable_user_id").val('');
    $("#ubication").val('');
}

function saveNursery() {
    var error = false;
    var id = $("#id").val();
    var name = $("#name").val();
    var responsable_user_id = $("#responsable_user_id").val();
    var ubication = $("#ubication").val();

    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !responsable_user_id && (error = true) && toastr.warning('El Responsable es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'name': name,
        'responsable_user_id': responsable_user_id,
        'ubication': ubication
    };

    if(id) {
        data['id'] = id;
        sendUpsertNurseryRequest(`/admin/nurseries/${id}`, 'put', data);
    } else {
        sendUpsertNurseryRequest('/admin/nurseries', 'post', data);
    }
}

function sendUpsertNurseryRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanNurseryForm();
            $("#addModal").modal('hide');
            loadNurseries();

            Swal.fire({
                title: result.data.question,
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
