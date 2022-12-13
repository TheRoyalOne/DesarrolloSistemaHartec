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

    loadMaterialLeavings();
}

function loadMaterialLeavings() {
    $.ajax({
        url: '/admin/material-leavings',
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
        var route = `/admin/material-leavings/${row.id}`;
        cleanMaterialsTable();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                material_leaving = result.data;
                fillMaterialLeavingForm(material_leaving);
                $("#addModal").modal('show');
            },
            error(err) {
                console.log(err);
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Salida del Material.',
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
            text: `Se eliminará la salida del material ${row.id}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/material-leavings/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadMaterialLeavings();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la salida de material correctamente.',
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

function fillMaterialLeavingForm(data) {
    var date = new Date(data.leaving_date);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    $('#id').val(data.id);
    $('#workshop_id').val(data.workshop_id);
    $('#technical_user_id').val(data.technical_user_id);
    $('#leaving_date').val(dateFormat);

    console.log(data.leaving_material);
    populateMaterialTable(data.leaving_material);
}

function cleanMaterialLeavingForm() {
    $('#id').val('');
    $('#workshop_id').val('');
    $('#technical_user_id').val('');
    $('#leaving_date').val('');

    cleanMaterialsTable();
}

function saveMaterialLeaving() {
    var error = false;
    var id = $("#id").val();
    var workshop_id = $('#workshop_id').val();
    var technical_user_id = $('#technical_user_id').val();
    var leaving_date = $('#leaving_date').val();
    var leaving_material = getMaterials()

    !workshop_id && (error = true) && toastr.warning('El Taller es requerido.', 'Información incompleta!');
    !technical_user_id && (error = true) && toastr.warning('El Tecnico es requerido.', 'Información incompleta!');
    !leaving_date && (error = true) && toastr.warning('La fecha es requerida.', 'Información incompleta!');

    if(error) { return; }

    var data = {
        'workshop_id': workshop_id,
        'technical_user_id': technical_user_id,
        'leaving_date': leaving_date,
        'leaving_material': leaving_material
    }

    
    if(id) {
        data['id'] = id;
        console.log(id, data);
        sendUpsertMaterialLeavingRequest(`/admin/material-leavings/${id}`, 'put', data);
    } else {
        sendUpsertMaterialLeavingRequest('/admin/material-leavings', 'post', data);
    }
}

function getMaterials() {
    var materials = [];
    var pivots_ids = document.getElementsByName('pivot_id');
    var material_ids = document.getElementsByName('material_id');
    var material_amounts = document.getElementsByName('material_amount');

    for(var i = 0; i < material_ids.length; i++) {
        materials.push({
            'pivot_id': pivots_ids[i].value,
            'material_id': material_ids[i].value,
            'material_amount': material_amounts[i].value
        });
    }

    return materials;
}

function populateMaterialTable(leaving_materials) {
    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('materials-table').getElementsByTagName('tbody')[0];

    for(var i = 0; i < leaving_materials.length; i++) {
        // Agregar fila (vacia)
        var newRow = tbodyRef.insertRow();
        
        // Construir celdas
        var rowHtmlContent = [
            `<input type="hidden" name="pivot_id" value="0">`,
            `<input type="hidden" name="material_id" value="${leaving_materials[i].material_id}">`,
            `<td>${ leaving_materials[i].material_name }</td>`,
            `<td><input type="number" name="material_amount" value="${leaving_materials[i].material_amount}" min="1" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad"></td>`,
            `<td><button type="button" onclick="removeMaterialsRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
}


function addMaterialRow() {
    // Recabar informacion del material, validar
    var error = false;
    var select_materials_id = document.getElementById('select_materials_id').value;
    // var select_adoption_id = document.getElementsByName('select_adoption_id').value;
    var materials_ids = document.getElementsByName('material_id');

    !select_materials_id && (error = true) && toastr.warning('Selecciona un material.', 'Información incompleta!');

    for(var i = 0; i < materials_ids.length; i++) {
        // Elemento previamente agregado
        if (materials_ids[i].value == select_materials_id) {
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
        `<input type="hidden" name="material_id" value="${select_materials_id}">`,
        `<td>${ workshopMaterials[select_materials_id] }</td>`,
        `<td><input type="number" name="material_amount" value="1" min="1" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad"></td>`,
        `<td><button type="button" onclick="removeMaterialsRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
    ].join('');

    // Añadir celdas a la fila
    newRow.innerHTML = rowHtmlContent;
}


function removeMaterialsRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function cleanMaterialsTable() {
    var tbodyRef = document.getElementById('materials-table').getElementsByTagName('tbody')[0];
    tbodyRef.innerHTML = '';
}

function sendUpsertMaterialLeavingRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanMaterialLeavingForm();
            $("#addModal").modal('hide');
            loadMaterialLeavings();

            Swal.fire({
                title: result.data.id,
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