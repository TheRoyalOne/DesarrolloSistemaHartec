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

    loadSpecies();
}

function loadSpecies() {
    $.ajax({
        url: '/Desarrollo/public/admin/species',
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

// function logotypeColumnFormatter(value, row, index, field) {
//     return [
//         `<img src="${row.logotype_url}" alt="${row.logotype_name}" height="100">`
//     ].join('');
// }

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
        var route = `/Desarrollo/public/admin/species/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                species = result.data;
                fillSpeciesForm(species);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Especie.',
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
            text: `Se eliminará la especie ${row.scientific_name}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/Desarrollo/public/admin/species/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadSpecies();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la especie correctamente.',
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

function fillSpeciesForm(data) {
    $('#id').val(data.id);
    $('#name').val(data.name);
    $('#scientific_name').val(data.scientific_name);
    $('#recovery_fee_a').val(data.recovery_fee_a);
    $('#recovery_fee_b').val(data.recovery_fee_b);
    $('#recovery_fee_c').val(data.recovery_fee_c);
    $('#spec_1').val(data.spec_1);
    $('#spec_2').val(data.spec_2);
    $('#spec_3').val(data.spec_3);
    $('#spec_4').val(data.spec_4);
    $('#spec_5').val(data.spec_5);
    $('#spec_6').val(data.spec_6);
    $('#observations').val(data.observations);
    // $('#picture').val(data.picture);
}

function cleanSpeciesForm() {
    $('#id').val('');
    $('#name').val('');
    $('#scientific_name').val('');
    $('#recovery_fee_a').val('');
    $('#recovery_fee_b').val('');
    $('#recovery_fee_c').val('');
    $('#spec_1').val('');
    $('#spec_2').val('');
    $('#spec_3').val('');
    $('#spec_4').val('');
    $('#spec_5').val('');
    $('#spec_6').val('');
    $('#observations').val('');
    $('#picture').val('');
}

$('#form').on('submit',(function(e) {
    e.preventDefault();

    var error = false;
    var id = $("#id").val();
    var scientific_name = $("#scientific_name").val();
    var name = $("#name").val();

    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !scientific_name.trim() && (error = true) && toastr.warning('El Nombre Cientifico es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = new FormData(this);

    if(id) {
        // data.append('id', id);
        sendUpsertSpeciesRequest(`/Desarrollo/public/admin/species/${id}?_method=PUT`, 'post', data);
    } else {
        sendUpsertSpeciesRequest('/Desarrollo/public/admin/species', 'post', data);
    }
}));

function sendUpsertSpeciesRequest(url, type, data) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            console.log(result);
            cleanSpeciesForm();
            $("#addModal").modal('hide');
            loadSpecies();

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
