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

    loadAdoptions();
}

function loadAdoptions() {
    $.ajax({
        url: '/public/admin/adoptions',
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
        var route = `/public/admin/adoptions/${row.id}`;
        cleanSpeciesTable();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                adoption = result.data;
                fillAdoptionForm(adoption);
                $("#addModal").modal('show');
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
}

var deleteColumnEvent = {
    'click .btn-delete': function(e, value, row, index) {
        Swal.fire({
            allowOutsideClick: true,
            title: '¿Esta seguro?',
            text: `Se eliminará la adopción ${row.code_event}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/public/admin/adoptions/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadAdoptions();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la adopción correctamente.',
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

function fillAdoptionForm(data) {
    var date = new Date(data.adoption_date);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var dateFormat = date.getFullYear() + "-" + (month) + "-" + (day);

    $('#id').val(data.id);
    $('#adoption_date').val(dateFormat);
    $('#adoption_time').val(data.adoption_time);
    $('#select_educative_institution_id').val(data.educative_institution_id);
    $('#select_sponsor_id').val(data.sponsor_id);
    $('#technical_user_id').val(data.technical_user_id);
    $('#select_event_id').val(data.event_id);
    $('#select_species_id').val(data.species_id);
    $('#code_event').val(data.code_event);
    $('#name').val(data.name);
    $('#phone').val(data.phone);
    $('#address').val(data.address);
    $('#email').val(data.email);
    $('#postal_code').val(data.postal_code);
    $('#longitude').val(data.longitude);
    $('#latitude').val(data.latitude);

    populateSpeciesTable(data.adoption_species,date.getFullYear());
}

function cleanAdoptionForm() {
    $('#id').val('');
    $('#adoption_date').val('');
    $('#adoption_time').val('');
    $('#select_educative_institution_id').val('');
    $('#select_sponsor_id').val('');
    $('#technical_user_id').val('');
    $('#select_event_id').val('');
    $('#select_species_id').val('');
    $('#code_event').val('');
    $('#name').val('');
    $('#phone').val('');
    $('#address').val('');
    $('#email').val('');
    $('#postal_code').val('');
    $('#longitude').val('');
    $('#latitude').val('');

    cleanSpeciesTable();
}

function saveAdoption() {
    var error = false;
    var id = $("#id").val();
    var adoption_date = $('#adoption_date').val();
    var adoption_time = $('#adoption_time').val();
    var educative_institution_id = $('#select_educative_institution_id').val();
    var sponsor_id = $('#select_sponsor_id').val();
    var technical_user_id = $('#technical_user_id').val();
    var event_id = $('#select_event_id').val();
    var code_event = $('#code_event').val();
    var adoption_species = getAdoptionSpecies();

    !adoption_date && (error = true) && toastr.warning('La Fecha es requerida.', 'Información incompleta!');
    !adoption_time && (error = true) && toastr.warning('La Hora es requerida.', 'Información incompleta!');
    !educative_institution_id && (error = true) && toastr.warning('La Institución es requerida.', 'Información incompleta!');
    !sponsor_id && (error = true) && toastr.warning('El Patrocinador es requerido.', 'Información incompleta!');
    !technical_user_id && (error = true) && toastr.warning('El Técnico es requerido.', 'Información incompleta!');
    !event_id && (error = true) && toastr.warning('El Evento es requerido.', 'Información incompleta!');
    !code_event && (error = true) && toastr.warning('El Código del Evento es requerido.', 'Información incompleta!');
    adoption_species.length <= 0 && (error = true) && toastr.warning('Las Especies son requeridas.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'adoption_date': adoption_date,
        'adoption_time': adoption_time,
        'educative_institution_id': educative_institution_id,
        'sponsor_id': sponsor_id,
        'technical_user_id': technical_user_id,
        'event_id': event_id,
        'code_event': code_event,
        'adoption_species': adoption_species
    }

    if(id) {
        data['id'] = id;
        sendUpsertAdoptionRequest(`/public/admin/adoptions/${id}`, 'put', data);
    } else {
        sendUpsertAdoptionRequest('/public/admin/adoptions', 'post', data);
    }
}

function sendUpsertAdoptionRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanAdoptionForm();
            $("#addModal").modal('hide');
            loadAdoptions();

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

/** Código Autogenerado */
function generateAdoptionCode() {
    var code = '###-###-######';
    var select_sponsor_id = document.getElementById('select_sponsor_id').value;
    var select_educative_institution_id = document.getElementById('select_educative_institution_id').value;
    var select_event_id = document.getElementById('select_event_id').value;
    var adoption_date = document.getElementById('adoption_date').value;

    if(select_sponsor_id != undefined && select_sponsor_id != null) {
        code = ((sponsors?.[select_sponsor_id]?.prefix_code_event) ?? '###') + '-';
    } else if(select_educative_institution_id) {
        code = ((educativeInstitutions?.[select_educative_institution_id]?.prefix_code_event) ?? '###') + '-';
    }

    if(select_event_id != undefined && select_event_id != null) {
        code += ((events?.[select_event_id]?.prefix_code) ?? '###') + '-';
    }

    if(adoption_date.trim() != '') {
        code += adoption_date;
    } else {
        code += '######';
    }

    document.getElementById('code_event').value = code;
    addYearToTag_Tree();
}

function addYearToTag_Tree(){
    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('species-table').getElementsByTagName('tbody')[0];
    
    // for(var i = 0; i < adoption_species.length; i++) {
    //     // Agregar fila (vacia)
    //     var newRow = tbodyRef.insertRow();

    //     // Construir celdasadoption_species[i].species_id
    //     var rowHtmlContent = [
    //         `<input type="hidden" name="pivot_id" value="${adoption_species[i].pivot_id}">`,
    //         `<input type="hidden" name="species_id" value="${adoption_species[i].species_id}">`,
    //         `<td>${adoption_species[i].species_name}</td>`,
    //         `<td>${adoption_species[i].species_scientific_name ?? '-'}</td>`,
    //         `<td><input class="form-control" value="${anio_adopcion??'####'}-${adoption_species[i].species_id??'##'}-####" placeholder="Etiqueta de Arboles" disabled="" name="tag_tree_${i}" type="text" id="tag_tree_${i}"></td>`,
    //         //`<td>${anio_adopcion??'####'}-${adoption_species[i].species_id??'##'}-####</td>`,
    //         `<td><input type="number" name="species_amount" value="${adoption_species[i].species_amount}" min="1" class="input-group-sm form-control" placeholder="Cantidad"></td>`,
    //         `<td><button type="button" onclick="removeSpeciesRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
    //     ].join('');

    //     // Añadir celdas a la fila
    //     newRow.innerHTML = rowHtmlContent;
    // }
}

/** Tabla de Especies */
function addSpeciesRow() {
    // Recabar informacion de la especie, validar
    var error = false;
    var select_species_id = document.getElementById('select_species_id').value;
    var species_ids = document.getElementsByName('species_id');
    var adoption_date = document.getElementById('adoption_date').value.split('-');
    console.log(adoption_date[0]);
    var anio_adopcion = adoption_date[0];
    if(anio_adopcion.length == 0){
        anio_adopcion = '####'
    }

    !select_species_id && (error = true) && toastr.warning('Selecciona una Especie.', 'Información incompleta!');

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

    // Construir celdas
    var rowHtmlContent = [
        `<input type="hidden" name="pivot_id" value="0">`,
        `<input type="hidden" name="species_id" value="${select_species_id}">`,
        `<td>${ allSpecies[select_species_id]['name'] }</td>`,
        `<td>${ allSpecies[select_species_id]['scientific_name'] ?? '-' }</td>`,
        `<td><input class="form-control" value="${anio_adopcion}-${select_species_id}-####" placeholder="Etiqueta de Arboles" disabled="" name="tag_tree" type="text" id="tag_tree_${i}"></td>`,
        `<td><input type="number" name="species_amount" value="1" min="1" class="input-group-sm form-control" placeholder="Cantidad"></td>`,
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

function populateSpeciesTable(adoption_species,anio_adopcion) {
    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('species-table').getElementsByTagName('tbody')[0];

    for(var i = 0; i < adoption_species.length; i++) {
        // Agregar fila (vacia)
        var newRow = tbodyRef.insertRow();

        // Construir celdas
        var rowHtmlContent = [
            `<input type="hidden" name="pivot_id" value="${adoption_species[i].pivot_id}">`,
            `<input type="hidden" name="species_id" value="${adoption_species[i].species_id}">`,
            `<td>${adoption_species[i].species_name}</td>`,
            `<td>${adoption_species[i].species_scientific_name ?? '-'}</td>`,
            // `<td><input class="form-control" name="tag_tree" value="${anio_adopcion??'####'}-${adoption_species[i].species_id??'##'}-####" placeholder="Etiqueta de Arboles" disabled="" type="text" id="tag_tree_${i}"></td>`,
            `<td><input type="number" name="species_amount" value="${adoption_species[i].species_amount}" min="1" class="input-group-sm form-control" placeholder="Cantidad"></td>`,
            `<td><button type="button" onclick="removeSpeciesRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
}

function getAdoptionSpecies() {
    var adoption_species = [];
    var pivots_ids = document.getElementsByName('pivot_id');
    var species_ids = document.getElementsByName('species_id');
    var tag_trees = document.getElementsByTagName('tag_tree');
    var species_amounts = document.getElementsByName('species_amount');

    for(var i = 0; i < species_ids.length; i++) {
        adoption_species.push({
            'pivot_id': pivots_ids[i].value,
            'species_id': species_ids[i].value,
            // 'tag_tree': tag_trees[i].value,
            'species_amount': species_amounts[i].value
        });
    }

    return adoption_species;
}

function printTags(){
    var adoptionsSpecies = getAdoptionSpecies();
    console.log("adoptionsSpecies");
}