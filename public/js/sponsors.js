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

    loadSponsors();
}

function loadSponsors() {
    $.ajax({
        url: '/admin/sponsors',
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
        `<img src="${row.logotype_url}" alt="${row.logotype_name}" height="100">`
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
        var route = `/admin/sponsors/${row.id}`;
        cleanSponsorForm();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                console.log(result.data);
                sponsor = result.data;
                fillSponsorForm(sponsor);
                $("#addModal").modal('show');
            },
            error: function(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Salida del Árbol.',
                    text: `Desactive el add block para realizar esta accion`,
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
            text: `Se eliminará al patrocinador ${row.enterprise_name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/sponsors/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadSponsors();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó al patrocinador correctamente.',
                            icon: 'success'
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            allowOutsideClick: true,
                            title: 'Error al eliminar.',
                            text: `Desactive el add block para realizar esta accion`,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
}

function fillSponsorForm(data) {
    $('#id').val(data.id);
    $('#enterprise_name').val(data.enterprise_name);
    $('#social_reason').val(data.social_reason);
    $('#rfc').val(data.rfc);
    $('#prefix_code_event').val(data.prefix_code_event);
    $('#name').val(data.name);
    $('#firstname').val(data.firstname);
    $('#lastname').val(data.lastname);
    $('#email').val(data.email);
    $('#cellphone').val(data.cellphone);
    $('#address').val(data.address);
    $('#numE').val(data.numI);
    $('#numI').val(data.numI);
    $('#colony').val(data.colony);
    $('#postal_code').val(data.postal_code);
    $('#observations').val(data.observations);
    $('#website').val(data.website);
    $('#logotype').val(data.logotype);
    $('#id_event_adoptions').val(data.id_event_adoptions);
    $('#id_event_workshops').val(data.id_event_workshops);
    
    $('#size').val(data.size);
    $('#roll').val(data.roll);
    $('#end_sponsorship').val(toDate(data.end_sponsorship));
    $('#user').val(data.user);
}

function toDate(date) {
    var date = new Date(date);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    return dateFormat;
}

function cleanSponsorForm() {
    $('#id').val('');
    $('#enterprise_name').val('');
    $('#social_reason').val('');
    $('#rfc').val('');
    $('#prefix_code_event').val('');
    $('#name').val('');
    $('#firstname').val('');
    $('#lastname').val('');
    $('#email').val('');
    $('#cellphone').val('');
    $('#address').val('');
    $('#numE').val('');
    $('#numI').val('');
    $('#colony').val('');
    $('#postal_code').val('');
    $('#observations').val('');
    $('#website').val('');
    $('#logotype').val('');
    $('#id_event_adoptions').val('');
    $('#id_event_workshops').val('');
    $('#user').val('');
}

$('#form').on('submit',(function(e) {
    e.preventDefault();

    var error = false;
    var id = $('#id').val();
    var enterprise_name = $('#enterprise_name').val();
    var social_reason = $('#social_reason').val();
    var prefix_code_event = $('#prefix_code_event').val();
    var name = $('#name').val();
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var address = $('#address').val();
    var numE = $('#numE').val();
    // var namI = $('#numI').val();
    var colony = $('#colony').val();
    var postal_code = $('#postal_code').val();
    var logotype = $('#logotype').val();
    var size = $('#size').val();
    var roll = $('#roll').val();
    var end_sponsorship = $('#end_sponsorship').val();
    var user = $('#user').val();


    !enterprise_name.trim() && (error = true) && toastr.warning('El Nombre de la Compañia es requerido.', 'Información incompleta!');
    !social_reason.trim() && (error = true) && toastr.warning('La Razón Social es requerida.', 'Información incompleta!');
    !prefix_code_event.trim() && (error = true) && toastr.warning('El Prefijo es requerido.', 'Información incompleta!');
    !name.trim() && (error = true) && toastr.warning('El Nombre del Contacto es requerido.', 'Información incompleta!');
    !firstname.trim() && (error = true) && toastr.warning('El Apellido Paterno del Contacto es requerido.', 'Información incompleta!');
    !lastname.trim() && (error = true) && toastr.warning('El Apellido Materno del Contacto es requerido.', 'Información incompleta!');
    !address.trim() && (error = true) && toastr.warning('La Dirección del Contacto es requerida.', 'Información incompleta!');
    !numE.trim() && (error = true) && toastr.warning('El Numero Exterior es requerido.', 'Información incompleta!');
    // !prefix_code_event.trim() && (error = true) && toastr.warning('El Nombre del Contacto es requerido.', 'Información incompleta!');
    !colony.trim() && (error = true) && toastr.warning('La Colonia del Contacto es requerida.', 'Información incompleta!');
    !postal_code.trim() && (error = true) && toastr.warning('El Codigo Postal del Contacto es requerido.', 'Información incompleta!');
    !end_sponsorship.trim() && (error = true) && toastr.warning('El Codigo Postal del Contacto es requerido.', 'Información incompleta!');
    // !id && !logotype.trim() && (error = true) && toastr.warning('El Logotipo es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = new FormData(this);
    data.append('size',size);
    data.append('roll',roll);
    data.append('end_sponsorship',end_sponsorship);
    data.append('user',user);


    if(id) {
        // data.append('id', id);
        sendUpsertEducativeInstitutionRequest(`/admin/sponsors/${id}?_method=PUT`, 'post', data);
    } else {
        sendUpsertEducativeInstitutionRequest('/admin/sponsors', 'post', data);
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
            console.log(data);
            console.log(result);
            cleanSponsorForm();
            $("#addModal").modal('hide');
            loadSponsors();

            Swal.fire({
                title: result.data.enterprise_name,
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
