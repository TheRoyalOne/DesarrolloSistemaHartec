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

    loadProfiles();
}

function loadProfiles() {
    $.ajax({
        url: '/admin/profiles',
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
        var route = `/admin/profiles/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                profile = result.data;
                fillProfileForm(profile);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Perfil.',
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
            text: `Se eliminará el perfil ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/profiles/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadProfiles();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el perfil correctamente.',
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

function fillProfileForm(data) {
    $("#id").val(data.id);
    $("#name").val(data.name);
}

function cleanProfileForm() {
    $("#id").val('');
    $("#name").val('');
}

function saveProfile() {
    var error = false;
    var id = $("#id").val();
    var name = $('#name').val();

    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'name': name
    };

    if(id) {
        data['id'] = id;
        sendUpsertProfileRequest(`/admin/profiles/${id}`, 'put', data);
    } else {
        sendUpsertProfileRequest('/admin/profiles', 'post', data);
    }
}

function sendUpsertProfileRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanProfileForm();
            $("#addModal").modal('hide');
            loadProfiles();

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