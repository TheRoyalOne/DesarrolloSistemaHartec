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

    loadSurvivalQuestions();
}

function loadSurvivalQuestions() {
    $.ajax({
        url: '/admin/survival-questions',
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
        var route = `/admin/survival-questions/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                survival_question = result.data;
                fillSurvivalQuestionForm(survival_question);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información de la Pregunta de Sobrevivencia.',
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
            text: `Se eliminará la pregunta de sobrevivencia ${row.question} y su respectiva respuesta.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/survival-questions/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadSurvivalQuestions();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó la pregunta de sobrevivencia correctamente.',
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

function fillSurvivalQuestionForm(data) {
    $("#id").val(data.id);
    $("#question").val(data.question);
}

function cleanSurvivalQuestionForm() {
    $("#id").val('');
    $("#question").val('');
}

function saveSurvivalQuestion() {
    var error = false;
    var id = $("#id").val();
    var question = $('#question').val();

    !question.trim() && (error = true) && toastr.warning('La Pregunta es requerida.', 'Información incompleta!');

    if(error) {
        return;
    }

    var data = {
        'question': question
    };

    if(id) {
        data['id'] = id;
        sendUpsertSurvivalQuestionRequest(`/admin/survival-questions/${id}`, 'put', data);
    } else {
        sendUpsertSurvivalQuestionRequest('/admin/survival-questions', 'post', data);
    }
}

function sendUpsertSurvivalQuestionRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanSurvivalQuestionForm();
            $("#addModal").modal('hide');
            loadSurvivalQuestions();

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