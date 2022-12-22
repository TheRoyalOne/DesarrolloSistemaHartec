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

    loadTreeLeavings();
}

function loadTreeLeavings() {
    $.ajax({
        url: '/public/admin/tree-leavings',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            console.log(result.data)
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
        var route = `/public/admin/tree-leavings/${row.id}`;
        cleanSpeciesTable();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                tree_leaving = result.data;
                fillTreeLeavingForm(tree_leaving);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Salida del Árbol.',
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
            text: `Se eliminará la salida del árbol ${row.code_event}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/public/admin/tree-leavings/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadTreeLeavings();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la salida de árbol correctamente.',
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

function fillTreeLeavingForm(data) {
    var date = new Date(data.leaving_date);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    $('#id').val(data.id);
    $('#select_adoption_id').val(data.adoption_id);
    // $('#nursery_id').val(data.nursery_id);
    // $('#species_id').val(data.species_id);
    // $('#amount').val(data.amount);
    $('#labels').val(data.labels);
    $('#technical_user_id').val(data.technical_user_id);
    $('#leaving_date').val(dateFormat);

    populateSpeciesTable(data.tree_leaving_species);
}

function cleanTreeLeavingForm() {
    $('#id').val('');
    $('#select_adoption_id').val('');
    // $('#nursery_id').val('');
    // $('#species_id').val('');
    // $('#amount').val('');
    $('#labels').val('');
    $('#technical_user_id').val('');
    $('#leaving_date').val('');
    $('#select_species_id').val('');
    // document.getElementById('select_adoption_id').value = '';

    cleanSpeciesTable();
}

function saveTreeLeaving() {
    var error = false;
    var id = $("#id").val();
    var select_adoption_id = $('#select_adoption_id').val();
    // var nursery_id = $('#nursery_id').val();
    // var species_id = $('#species_id').val();
    // var amount = $('#amount').val();
    var labels = $('#labels').val();
    var technical_user_id = $('#technical_user_id').val();
    var leaving_date = $('#leaving_date').val();
    var tree_leaving_species = getAdoptionSpecies();

    !select_adoption_id && (error = true) && toastr.warning('El Evento de Adopción es requerido.', 'Información incompleta!');
    // !nursery_id && (error = true) && toastr.warning('El Vivero es requerido.', 'Información incompleta!');
    // !species_id && (error = true) && toastr.warning('Las Especies son requeridas.', 'Información incompleta!');
    // !amount && (error = true) && toastr.warning('El Numero es requerido.', 'Información incompleta!');
    !labels && (error = true) && toastr.warning('Las Etiquetas son requeridas.', 'Información incompleta!');
    !technical_user_id && (error = true) && toastr.warning('El Técnico es requerido.', 'Información incompleta!');
    !leaving_date && (error = true) && toastr.warning('La Fecha es requerida.', 'Información incompleta!');
    tree_leaving_species.length <= 0 && (error = true) && toastr.warning('Las Especies son requeridas.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'adoption_id': select_adoption_id,
        // 'nursery_id': nursery_id,
        // 'species_id': species_id,
        // 'amount': amount,
        'labels': labels,
        'technical_user_id': technical_user_id,
        'leaving_date': leaving_date,
        'tree_leaving_species': tree_leaving_species
    }

    if(id) {
        data['id'] = id;
        sendUpsertTreeLeavingRequest(`/public/admin/tree-leavings/${id}`, 'put', data);
    } else {
        sendUpsertTreeLeavingRequest('/public/admin/tree-leavings', 'post', data);
    }
}

function sendUpsertTreeLeavingRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanTreeLeavingForm();
            $("#addModal").modal('hide');
            loadTreeLeavings();

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

/** Cargar lista de especies del Evento (Adopción) */
function fetchAdoptionSpecies() {
    var select_adoption_id = document.getElementById('select_adoption_id').value;

    if(!select_adoption_id) {
        return;
    }

    var route = `/public/admin/adoptions/${select_adoption_id}`;
    
    cleanSpeciesTable();
    document.getElementById('select_species_id').value = '';

    Swal.fire({
        allowOutsideClick: false,
        title: 'Espere',
        text: 'Cargando información.',
        didOpen: () => {
          Swal.showLoading();
        }
    });

    $.ajax({
        url: route,
        type: 'get',
        dataType: 'json',
        success: function(result)
        {
            adoption = result.data;
            populateSpeciesTable(adoption.adoption_species);
            // $("#addModal").modal('show');
            Swal.close();
        },
        error(err) {
            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar información de la Adopción.',
                text: `${err.error.user_message}`,
                icon: 'error'
            });
        }
    });
}

/** Tabla de Especies */
function addSpeciesRow() {
    // Recabar informacion del material, validar
    var error = false;
    var select_species_id = document.getElementById('select_species_id').value;
    // var select_adoption_id = document.getElementsByName('select_adoption_id').value;
    var species_ids = document.getElementsByName('species_id');

    !select_species_id && (error = true) && toastr.warning('Selecciona una Especie.', 'Información incompleta!');
    // !select_adoption_id && (error = true) && toastr.warning('Selecciona un Evento de Adopción .', 'Información incompleta!');

    for(var i = 0; i < species_ids.length; i++) {
        // Elemento previamente agregado
        if (species_ids[i].value == select_species_id) {
            error = true;
            toastr.warning('Epecie previamente agregada.', 'Alerta!');
            break;
        }
    }

    if (error) { return; }

    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('species-table').getElementsByTagName('tbody')[0];

    // Agregar fila (vacia)
    var newRow = tbodyRef.insertRow();

    console.log('adding row: ', nurseries);

    // Construir celdas
    var rowHtmlContent = [
        `<input type="hidden" name="pivot_id" value="0">`,
        `<input type="hidden" name="species_id" value="${select_species_id}">`,
        `<td>${ allSpecies[select_species_id]['name'] }</td>`,
        `<td>${ allSpecies[select_species_id]['scientific_name'] ?? '-' }</td>`,
        `<td><div class="input-group">
                <input type="number" name="species_amount" value="1" min="1" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad">
                <div class="input-group-append">
                    <span class="input-group-text">/0</span>
                </div>
        </div></td>`,
        `<td>${createSelctHtml(null, nurseries, '', 'nursery_id')}</td>`,
        `<td><button type="button" onclick="removeSpeciesRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
    ].join('');

    // Añadir celdas a la fila
    newRow.innerHTML = rowHtmlContent;
}

function removeSpeciesRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function cleanSpeciesTable() {
    var tbodyRef = document.getElementById('species-table').getElementsByTagName('tbody')[0];
    tbodyRef.innerHTML = '';
}

function populateSpeciesTable(tree_leaving_species) {
    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('species-table').getElementsByTagName('tbody')[0];

    for(var i = 0; i < tree_leaving_species.length; i++) {
        // Agregar fila (vacia)
        var newRow = tbodyRef.insertRow();

        console.log('populateSpeciesTable');

        // Construir celdastree_leaving_species
        var rowHtmlContent = [
            `<input type="hidden" name="pivot_id" value="${tree_leaving_species[i].pivot_id}">`,
            `<input type="hidden" name="species_id" value="${tree_leaving_species[i].species_id}">`,
            `<td>${tree_leaving_species[i].species_name}</td>`,
            `<td>${tree_leaving_species[i].species_scientific_name ?? '-'}</td>`,
            `<td><div class="input-group">
                <input type="number" name="species_amount" value="${tree_leaving_species[i].species_amount}" min="0" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad">
                <div class="input-group-append">
                    <span class="input-group-text">/${tree_leaving_species[i].species_amount}</span>
                </div>
            </div></td>`,
            `<td>${createSelctHtml(tree_leaving_species[i].nursery_id, nurseries, '', 'nursery_id')}</td>`,
            `<td><button type="button" onclick="removeSpeciesRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
}

// Crear select
function createSelctHtml(inputValue, dataCollection, inputId, inputName, keyField = 'id', textField = 'name', placeholder = false) {
    var optionsHtml = [];

    if(placeholder && !inputValue) {
        optionsHtml.push(`<option hidden selected value="">Selecciona...</option>`);
    }

    dataCollection = Object.values(dataCollection);
    for(var i = 0; i < dataCollection.length; i++) {
        optionsHtml.push(`<option value="${ dataCollection[i][keyField] }" ${inputValue == dataCollection[i][keyField] ? 'selected':''}>${ dataCollection[i][textField] }</option>`);
    }

    var selectHtmlContent = [
        `<select id="${inputId}" name="${inputName}" value="${inputValue}" class="form-control">`,
        ...optionsHtml,
        `</select>`
    ].join('');

    return selectHtmlContent;
}

function getAdoptionSpecies() {
    var adoption_species = [];
    var pivots_ids = document.getElementsByName('pivot_id');
    var species_ids = document.getElementsByName('species_id');
    var species_amounts = document.getElementsByName('species_amount');
    var nurseries_ids = document.getElementsByName('nursery_id');

    for(var i = 0; i < species_ids.length; i++) {
        adoption_species.push({
            'pivot_id': pivots_ids[i].value,
            'species_id': species_ids[i].value,
            'species_amount': species_amounts[i].value,
            'nursery_id': nurseries_ids[i].value
        });
    }

    return adoption_species;
}