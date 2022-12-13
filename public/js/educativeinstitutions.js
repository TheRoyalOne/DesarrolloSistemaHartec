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

    loadEducativeInstitutions();
}

function loadEducativeInstitutions() {
    $.ajax({
        url: '/admin/educative-institutions',
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

function logotypeColumnFormatter(value, row, index, field) {
    return [
        '<div class="container">',
            `<img src="${row.logotype_url}" alt="${row.logotype_name}" height="100" class="mx-auto d-block">`,
        '</div>'
    ].join('');
}

function contactInfoColumnFormatter(value, row, index, field) {
    return [
        `<span class="">${row.name} ${row.firstname ?? ''}</span>`
    ].join('');
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
        var route = `/admin/educative-institutions/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                educativeInstitution = result.data;
                fillEducativeInstitutionForm(educativeInstitution);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Institución Educativa.',
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
            text: `Se eliminará la institución educativa ${row.institution_name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/educative-institutions/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadEducativeInstitutions();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la institución educativa correctamente.',
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

function fillEducativeInstitutionForm(data) {
    $("#id").val(data.id);
    $('#institution_id').val(data.id);
    $("#institution_name").val(data.institution_name);
    $("#name").val(data.name);
    $("#firstname").val(data.firstname);
    $("#lastname").val(data.lastname);
    $("#email").val(data.email);
    $("#cellphone").val(data.cellphone);
    $("#institutional_charge").val(data.institutional_charge);
    $("#institutional_email").val(data.institutional_email);
    $("#institutional_phone").val(data.institutional_phone);
    $("#address").val(data.address);
    $("#numE").val(data.numE);
    $("#numI").val(data.numI);
    $("#colony").val(data.colony);
    $("#postal_code").val(data.postal_code);
    $("#observations").val(data.observations);
    $("#website").val(data.website);
}

function cleanEducativeInstitutionForm() {
    $("#id").val('');
    $('#institution_id').val('');
    $("#institution_name").val('');
    $("#name").val('');
    $("#firstname").val('');
    $("#lastname").val('');
    $("#email").val('');
    $("#cellphone").val('');
    $("#institutional_charge").val('');
    $("#institutional_email").val('');
    $("#institutional_phone").val('');
    $("#address").val('');
    $("#numE").val('');
    $("#numI").val('');
    $("#colony").val('');
    $("#postal_code").val('');
    $("#observations").val('');
    $("#website").val('');
    $("logotype").val(null);
}

$('#form').on('submit',(function(e) {
    e.preventDefault();

    var error = false;
    var id = $("#id").val();
    var institution_name = $("#institution_name").val();
    var name = $("#name").val();

    !institution_name.trim() && (error = true) && toastr.warning('El Nombre de Institucion es requerido.', 'Información incompleta!');
    !name.trim() && (error = true) && toastr.warning('El Nombre del Contacto es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = new FormData(this);

    if(id) {
        // data.append('id', id);
        sendUpsertEducativeInstitutionRequest(`/admin/educative-institutions/${id}?_method=PUT`, 'post', data);
    } else {
        sendUpsertEducativeInstitutionRequest('/admin/educative-institutions', 'post', data);
    }
}));

function sendUpsertEducativeInstitutionRequest(url, type, data) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            console.log(result);
            cleanEducativeInstitutionForm();
            $("#addModal").modal('hide');
            loadEducativeInstitutions();

            Swal.fire({
                title: result.data.institution_name,
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
