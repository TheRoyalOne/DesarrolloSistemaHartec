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

    loadWorkshops();
}

function loadWorkshops() {
    $.ajax({
        url: '/admin/workshops',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            let d = orderData(result.data);
            $('#table').bootstrapTable('load', d);
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

function orderData(data) {
    return data.sort((a, b) => {
        return new Date(a.workshop_date) - new Date(b.workshop_date)
    })
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
        var route = `/admin/workshops/${row.id}`;
        cleanWorkshopForm();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                workshop = result.data;
                console.log(workshop);
                fillWorkshopForm(workshop);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Registro de Taller.',
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
            text: `Se eliminará el registro de taller ${row.code_event}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/workshops/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadWorkshops();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el registro de taller correctamente.',
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
    var date = new Date(data.workshop_date);
    var day = ("0" + (date.getDate() + 1)).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    var rec_fee = ((events?.[data.event_id]?.[data.rec_fee_type]) ?? '-');
    
    $('#id').val(data.id);
    $('#select_sponsor_id').val(data.sponsor_id);
    $('#select_educative_institution_id').val(data.educative_institution_id);
    $('#select_event_id').val(data.event_id);
    $('#select_rec_fee_type').val(data.rec_fee_type);
    $('#rec_fee').val(rec_fee);
    $('#workshop_date').val(dateFormat);
    $('#workshop_time').val(data.workshop_time);
    $('#select_workshop_user_id').val(data.workshop_user_id);
    $('#code_event').val(data.code_event);

    populateMaterialsTable(data.workshop_materials);
}

function cleanWorkshopForm() {
    $('#id').val('');
    $('#select_sponsor_id').val('');
    $('#select_educative_institution_id').val('');
    $('#select_event_id').val('');
    $('#select_rec_fee_type').val('');
    $('#rec_fee').val('');
    $('#workshop_date').val('');
    $('#workshop_time').val('');
    $('#select_workshop_user_id').val('');
    $('#code_event').val('');

    cleanMaterialsTable();
}

function saveWorkshop() {
    var error = false;
    var id = $("#id").val();
    var sponsor_id = $('#select_sponsor_id').val();
    var educative_institution_id = $('#select_educative_institution_id').val();
    var event_id = $('#select_event_id').val();
    var rec_fee_type = $('#select_rec_fee_type').val();
    var rec_fee = $('#rec_fee').val();
    var workshop_date = $('#workshop_date').val();
    var workshop_time = $('#workshop_time').val();
    var workshop_user_id = $('#select_workshop_user_id').val();
    var code_event = $('#code_event').val();
    
    var workshop_materials = getWorkshopMaterials();
    !sponsor_id && (error = true) && toastr.warning('El Patrocinador es requerido.', 'Información incompleta!');
    !educative_institution_id && (error = true) && toastr.warning('La Institución es requerida.', 'Información incompleta!');
    !rec_fee_type && (error = true) && toastr.warning('El Tipo de Cuota es requerido.', 'Información incompleta!');
    !event_id && (error = true) && toastr.warning('El Evento es requerido.', 'Información incompleta!');
    !workshop_date && (error = true) && toastr.warning('La Fecha es requerida.', 'Información incompleta!');
    !workshop_time && (error = true) && toastr.warning('La Hora es requerida.', 'Información incompleta!');
    !workshop_user_id && (error = true) && toastr.warning('El Tallerista es requerido.', 'Información incompleta!');
    !rec_fee && (error = true) && toastr.warning('La Cuota de Recuperación es requerida.', 'Información incompleta!');
    !code_event.trim() && (error = true) && toastr.warning('La Fecha es requerida.', 'Información incompleta!');
    workshop_materials.length <= 0 && (error = true) && toastr.warning('Los Materiales son requeridos.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'sponsor_id': sponsor_id,
        'educative_institution_id': educative_institution_id,
        'rec_fee_type': rec_fee_type,
        'event_id': event_id,
        'workshop_date': workshop_date,
        'workshop_time': workshop_time,
        'workshop_user_id': workshop_user_id,
        'rec_fee': rec_fee,
        'code_event': code_event,
        'workshop_materials': workshop_materials
    }

    if(id) {
        data['id'] = id;
        sendUpsertWorkshopRequest(`/admin/workshops/${id}`, 'put', data);
    } else {
        sendUpsertWorkshopRequest('/admin/workshops', 'post', data);
    }
}

function sendUpsertWorkshopRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanWorkshopForm();
            $("#addModal").modal('hide');
            loadWorkshops();

            Swal.fire({
                title: result.data.code_event,
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

/** Establecer cuota */
function setRecFee() {
    var select_event_id = document.getElementById('select_event_id').value;
    var select_rec_fee_type = document.getElementById('select_rec_fee_type').value;

    if(select_event_id && select_rec_fee_type) {
        document.getElementById('rec_fee').value = ((events?.[select_event_id]?.[select_rec_fee_type]) ?? '-');
    }
}

/** Código Autogenerado */
function generateWorkshopCode() {
    var code = '###-###-######';
    var select_sponsor_id = document.getElementById('select_sponsor_id').value;
    var select_educative_institution_id = document.getElementById('select_educative_institution_id').value;
    var select_event_id = document.getElementById('select_event_id').value;
    var workshop_date = document.getElementById('workshop_date').value;

    if(select_sponsor_id != undefined && select_sponsor_id != null) {
        code = ((sponsors?.[select_sponsor_id]?.prefix_code_event) ?? '###') + '-';
    } else if(select_educative_institution_id) {
        code = ((educativeInstitutions?.[select_educative_institution_id]?.prefix_code_event) ?? '###') + '-';
    }

    if(select_event_id != undefined && select_event_id != null) {
        code += ((events?.[select_event_id]?.prefix_code) ?? '###') + '-';
    }

    if(workshop_date.trim() != '') {
        code += workshop_date;
    } else {
        code += '######';
    }

    document.getElementById('code_event').value = code;
}

/** Tabla de Materiales */
function addMaterialRow() {
    // Recabar informacion del material, validar
    var error = false;
    var select_workshop_material_id = document.getElementById('select_workshop_material_id').value;
    var workshop_materials_ids = document.getElementsByName('workshop_material_id');

    !select_workshop_material_id && (error = true) && toastr.warning('Selecciona un Material.', 'Información incompleta!');

    for(var i = 0; i < workshop_materials_ids.length; i++) {
        // Elemento previamente agregado
        if (workshop_materials_ids[i].value == select_workshop_material_id) {
            error = true;
            toastr.warning('Material previamente agregado.', 'Alerta!');
            break;
        }
    }

    if (error) { return; }

    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('materials-table').getElementsByTagName('tbody')[0];

    // Agregar fila (vacia)
    var newRow = tbodyRef.insertRow();

    // Construir celdas
    var rowHtmlContent = [
        `<input type="hidden" name="pivot_id" value="0">`,
        `<input type="hidden" name="workshop_material_id" value="${select_workshop_material_id}">`,
        `<td>${ workshopMaterials[select_workshop_material_id]['name'] }</td>`,
        `<td>${ workshopMaterials[select_workshop_material_id]?.['description'] ?? '-' }</td>`,
        `<td><input type="number" name="workshop_material_amount" value="1" min="1" class="input-group-sm form-control" placeholder="Cantidad"></td>`,
        `<td><button type="button" onclick="removeMaterialRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
    ].join('');

    // Añadir celdas a la fila
    newRow.innerHTML = rowHtmlContent;
}

function removeMaterialRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function cleanMaterialsTable() {
    var tbodyRef = document.getElementById('materials-table').getElementsByTagName('tbody')[0];
    tbodyRef.innerHTML = '';
}

function populateMaterialsTable(workshop_materials) {
    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('materials-table').getElementsByTagName('tbody')[0];

    for(var i = 0; i < workshop_materials.length; i++) {
        // Agregar fila (vacia)
        var newRow = tbodyRef.insertRow();

        // Construir celdas
        var rowHtmlContent = [
            `<input type="hidden" name="pivot_id" value="${workshop_materials[i].pivot_id}">`,
            `<input type="hidden" name="workshop_material_id" value="${workshop_materials[i].workshop_material_id}">`,
            `<td>${workshop_materials[i].workshop_material_name}</td>`,
            `<td>${workshop_materials[i].workshop_material_description ?? '-'}</td>`,
            `<td><input type="number" name="workshop_material_amount" value="${workshop_materials[i].workshop_material_amount}" min="1" class="input-group-sm form-control" placeholder="Cantidad"></td>`,
            `<td><button type="button" onclick="removeMaterialRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
}

function getWorkshopMaterials() {
    var workshop_materials = [];
    var pivots_ids = document.getElementsByName('pivot_id');
    var workshop_materials_ids = document.getElementsByName('workshop_material_id');
    var workshop_materials_amounts = document.getElementsByName('workshop_material_amount');

    for(var i = 0; i < workshop_materials_ids.length; i++) {
        workshop_materials.push({
            'pivot_id': pivots_ids[i].value,
            'workshop_material_id': workshop_materials_ids[i].value,
            'workshop_material_amount': workshop_materials_amounts[i].value
        });
    }

    return workshop_materials;
}