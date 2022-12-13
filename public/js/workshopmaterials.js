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

    loadWorkshopMaterials();
}

function loadWorkshopMaterials() {
    $.ajax({
        url: '/admin/workshop-materials',
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
        var route = `/admin/workshop-materials/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                workshopMaterial = result.data;
                fillWorkshopMaterialForm(workshopMaterial);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Material de Taller.',
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
            text: `Se eliminará el material de taller ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/workshop-materials/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadWorkshopMaterials();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el material de taller correctamente.',
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

function fillWorkshopMaterialForm(data) {
    $("#id").val(data.id);
    $("#name").val(data.name);
    $("#description").val(data.description);
}

function cleanWorkshopMaterialForm() {
    $("#id").val('');
    $("#name").val('');
    $("#description").val('');
}

function saveWorkshopMaterial() {
    var error = false;
    var id = $("#id").val();
    var name = $("#name").val();
    var description = $("#description").val();

    !name.trim() && (error = true) && toastr.warning('El nombre es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'name': name,
        'description': description
    };

    if(id) {
        data['id'] = id;
        sendUpsertWorkshopMaterialRequest(`/admin/workshop-materials/${id}`, 'put', data);
    } else {
        sendUpsertWorkshopMaterialRequest('/admin/workshop-materials', 'post', data);
    }
}

function sendUpsertWorkshopMaterialRequest(url, type, data) {
    console.log('Upsert...', type, data);
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            console.log('upsert-success', result);
            cleanWorkshopMaterialForm();
            $("#addModal").modal('hide');
            loadWorkshopMaterials();

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
