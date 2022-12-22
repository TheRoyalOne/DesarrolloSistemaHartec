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

    loadEvents();
}

function loadEvents() {
    $.ajax({
        url: '/public/admin/workshop-events/',
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
        // var route = baseURL + '/Getinfo/'+row.id;
        var route = `/public/admin/workshop-events/${row.id}`;
        updateid = row.id;
        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                workshop = result.data;

                switch(workshop.type) {
                    case 'Adopcion': 
                        fillAdoptionForm(workshop);
                        $("#addModaladoption").modal('show'); 
                        break;

                    case 'Taller': 
                        fillWorkshopForm(workshop);
                        $("#addModalWorkshop").modal('show'); 
                        break;

                    case 'Reforestacion': 
                        fillReforestationForm(workshop);
                        $("#addModalreforest").modal('show'); 
                        break;
                }


            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Evento.',
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
            text: `Se eliminará el evento ${row.name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/public/admin/workshop-events/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result){
                        loadEvents();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el evento correctamente.',
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

function fillWorkshopForm(data) {
    $("#event_id").val(data.id);
    $("#name_workshop").val(data.name);
    $("#prefix_code_workshop").val(data.prefix_code);
    $("#rec_fee_online").val(data.rec_fee_online);
    $("#rec_fee_presencial").val(data.rec_fee_presencial);
    $("#rec_fee_business").val(data.rec_fee_business);
    $("#rec_fee_online_kits").val(data.rec_fee_online_kits);
    $("#description_workshop").val(data.description);
}

function fillAdoptionForm(data) {
    $("#event_id").val(data.id);
    $("#name_adoption").val(data.name);
    $("#prefix_code_adoption").val(data.prefix_code);
    $("#donation_adoption").val(data.donation);
    $("#description_adoption").val(data.description);
}

function fillReforestationForm(data) {
    $("#event_id").val(data.id);
    $("#name_reforest").val(data.name);
    $("#prefix_code_reforest").val(data.prefix_code);
    $("#donation_reforest").val(data.donation);
    $("#description_reforest").val(data.description);
}

function cleanWorkshopForm() {
    $("#event_id").val('');
    $("#name_workshop").val('');
    $("#prefix_code_workshop").val('');
    $("#rec_fee_online").val('');
    $("#rec_fee_presencial").val('');
    $("#rec_fee_business").val('');
    $("#rec_fee_online_kits").val('');
    $("#description_workshop").val('');
}

function cleanAdoptionForm() {
    $("#event_id").val('');
    $("#name_adoption").val('');
    $("#prefix_code_adoption").val('');
    $("#donation_adoption").val('');
    $("#description_adoption").val('');
}

function cleanReforestationForm() {
    $("#event_id").val('');
    $("#name_reforest").val('');
    $("#prefix_code_reforest").val('');
    $("#donation_reforest").val('');
    $("#description_reforest").val('');
}

function saveWorkshop() {
    var error = false;
    var id = $("#event_id").val();
    var type = "Taller";
    var name = $("#name_workshop").val();
    var prefix_code = $("#prefix_code_workshop").val();
    var rec_fee_online = $("#rec_fee_online").val();
    var rec_fee_presencial = $("#rec_fee_presencial").val();
    var rec_fee_business = $("#rec_fee_business").val();
    var rec_fee_online_kits = $("#rec_fee_online_kits").val();
    var description = $("#description_workshop").val();
    
    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !prefix_code.trim() && (error = true) && toastr.warning('El Prefijo es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    let data = {
        'type': type,
        'name': name,
        'prefix_code': prefix_code,
        'rec_fee_online': rec_fee_online,
        'rec_fee_presencial': rec_fee_presencial,
        'rec_fee_business': rec_fee_business,
        'rec_fee_online_kits': rec_fee_online_kits,
        'description': description
    };

    if(id) {
        data['id'] = id;
        sendUpsertEventRequest(`/public/admin/workshop-events/${id}`, 'put', data);
    } else {
        sendUpsertEventRequest('/public/admin/workshop-events', 'post', data);
    }
}

function saveAdoption() {
    var error = false;
    var id = $("#event_id").val();
    var type = "Adopcion";
    var name = $("#name_adoption").val();
    var prefix_code = $("#prefix_code_adoption").val();
    var donation = $("#donation_adoption").val();
    var description = $("#description_adoption").val();

    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !prefix_code.trim() && (error = true) && toastr.warning('El Prefijo es requerido.', 'Información incompleta!');
    !donation.trim() && (error = true) && toastr.warning('La Cuota es requerida.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'type': type,
        'name': name,
        'prefix_code': prefix_code,
        'donation': donation,
        'description': description
    };

    if(id) {
        data['id'] = id;
        sendUpsertEventRequest(`/public/admin/workshop-events/${id}`, 'put', data);
    } else {
        sendUpsertEventRequest('/public/admin/workshop-events', 'post', data);
    }
}

function savereReforestation(){
    var error = false;
    var id = $("#event_id").val();
    var type = "Reforestacion";
    var name = $("#name_reforest").val();
    var prefix_code = $("#prefix_code_reforest").val();
    var donation = $("#donation_reforest").val();
    var description = $("#description_reforest").val();

    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !prefix_code.trim() && (error = true) && toastr.warning('El Prefijo es requerido.', 'Información incompleta!');
    !donation.trim() && (error = true) && toastr.warning('La Cuota es requerida.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'type': type,
        'name': name,
        'prefix_code': prefix_code,
        'donation': donation,
        'description': description
    };

    if(id) {
        data['id'] = id;
        sendUpsertEventRequest(`/public/admin/workshop-events/${id}`, 'put', data);
    } else {
        sendUpsertEventRequest('/public/admin/workshop-events', 'post', data);
    }
}

function sendUpsertEventRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanWorkshopForm();
            cleanAdoptionForm();
            cleanReforestationForm();

            $("#addModalWorkshop").modal('hide');
            $("#addModaladoption").modal('hide');
            $("#addModalreforest").modal('hide');

            loadEvents();

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
