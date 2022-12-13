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

    loadAnswers();
}

function loadAnswers() {
    $.ajax({
        url: '/admin/answers',
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
        var route = `/admin/answers/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                answer = result.data;
                fillAnswerForm(answer);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Pregunta.',
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
            text: `Se eliminará la pregunta ${row.question}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/answers/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadAnswers();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la pregunta correctamente.',
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

function fillAnswerForm(data) {
    $('#id').val(data.id);
    $('#answer_a').val(data.answer_a);
    $('#answer_b').val(data.answer_b);
    $('#answer_c').val(data.answer_c);
    $('#answer_d').val(data.answer_d);
    $('#answer_e').val(data.answer_e);
    $('#survival_question_id').val(data.survival_question_id);
}

function cleanAnswerForm() {
    $('#id').val('');
    $('#answer_a').val('');
    $('#answer_b').val('');
    $('#answer_c').val('');
    $('#answer_d').val('');
    $('#answer_e').val('');
    $('#survival_question_id').val('');
}

function saveAnswer() {
    var error = false;
    var id = $('#id').val();
    var survival_question_id = $('#survival_question_id').val();
    var answer_a = $('#answer_a').val();
    var answer_b = $('#answer_b').val();
    var answer_c = $('#answer_c').val();
    var answer_d = $('#answer_d').val();
    var answer_e = $('#answer_e').val();

    !survival_question_id && (error = true) && toastr.warning('La Pregunta es requerida.', 'Información incompleta!');
    !answer_a.trim() && (error = true) && toastr.warning('Se requiere un minimo de 3 respuestas.', 'Información incompleta!');
    !answer_b.trim() && (error = true) && toastr.warning('Se requiere un minimo de 3 respuestas.', 'Información incompleta!');
    !answer_c.trim() && (error = true) && toastr.warning('Se requiere un minimo de 3 respuestas.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'answer_a': answer_a,
        'answer_b': answer_b,
        'answer_c': answer_c,
        'answer_d': answer_d,
        'answer_e': answer_e,
        'survival_question_id': survival_question_id,
    }

    if(id) {
        data['id'] = id;
        sendUpsertAnswerRequest(`/admin/answers/${id}`, 'put', data);
    } else {
        sendUpsertAnswerRequest('/admin/answers', 'post', data);
    }
}

function sendUpsertAnswerRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanAnswerForm();
            $("#addModal").modal('hide');
            loadAnswers();

            Swal.fire({
                title: result.data.question,
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

