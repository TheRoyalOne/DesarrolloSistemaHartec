
/** Cargar lista de especies del Evento (Reforestacion) */
function fetchAdoptionSpecies() {
    var select_adoption_id = document.getElementById('select_adoption_id').value;

    if(!select_adoption_id) {
        return;
    }

    var route = `/admin/adoptions/${select_adoption_id}`;
    
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
            `<td>${tree_leaving_species[i].species_amount}</td>`,
            `<td><input type="number" name="dead_trees" max="${tree_leaving_species[i].species_amount}" min="0" value= "0" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad"> </td>`,
            `<td><input type="number" name="sick_trees" max="${tree_leaving_species[i].species_amount}" min="0" value= "0" class="input-group-sm form-control" style="text-align:right;" placeholder="Cantidad"> </td>`,
            /*  `<td>${createSelctHtml(tree_leaving_species[i].nursery_id, nurseries, '', 'nursery_id')}</td>`, */
            `<td><button type="button" onclick="removeSpeciesRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
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
    /* var nurseries_ids = document.getElementsByName('nursery_id'); */

    for(var i = 0; i < species_ids.length; i++) {
        adoption_species.push({
            'pivot_id': pivots_ids[i].value,
            'species_id': species_ids[i].value,
            'species_amount': species_amounts[i].value,
            /* 'nursery_id': nurseries_ids[i].value */
        });
    }

    return adoption_species;
}

function saveTreeCheck() {
    var error = false;
    var id = $("#id").val();
    var select_adoption_id = $('#select_adoption_id').val();
    // var nursery_id = $('#nursery_id').val();
    // var species_id = $('#species_id').val();
    // var amount = $('#amount').val();
    
    var technical_user_id = $('#technical_user_id').val();
    var leaving_date = $('#check_date').val();
    var tree_leaving_species = getAdoptionSpecies();

    !select_adoption_id && (error = true) && toastr.warning('El Evento de Adopción es requerido.', 'Información incompleta!');
    // !nursery_id && (error = true) && toastr.warning('El Vivero es requerido.', 'Información incompleta!');
    // !species_id && (error = true) && toastr.warning('Las Especies son requeridas.', 'Información incompleta!');
    // !amount && (error = true) && toastr.warning('El Numero es requerido.', 'Información incompleta!');
    /* !labels && (error = true) && toastr.warning('Las Etiquetas son requeridas.', 'Información incompleta!'); */
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
        'technical_user_id': technical_user_id,
        'leaving_date': leaving_date,
        'tree_leaving_species': tree_leaving_species
    }

    if(id) {
        data['id'] = id;
        sendUpsertTreeLeavingRequest(`/admin/tree-leavings/${id}`, 'put', data);
    } else {
        sendUpsertTreeLeavingRequest('/admin/tree-leavings', 'post', data);
    }
}


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
        url: '/admin/reforestation_checks',
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

