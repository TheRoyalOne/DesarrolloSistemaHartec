function toggleInputs() {
    
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

    loadQuestions();
}

function loadQuestions() {
    $.ajax({
        url: '/admin/questions',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            //cargar las preguntas
            window.questions = result.data;
            window.questions.forEach(function(question) { question.first_question = question.first_question ? 'Si' : 'No';  });

            $('#table').bootstrapTable('load', window.questions);
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
        var route = `/admin/questions/${row.id}`;
        cleanAnswersTable();

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result) {
                question = result.data;
                fillQuestionForm(question);
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
            text: `Se eliminará la pregunta de sobrevivencia ${row.sentence} y su respectiva respuesta.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/questions/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadQuestions();

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


function cleanAnswersTable() {
    var tbodyRef = document.getElementById('answer-table').getElementsByTagName('tbody')[0];
    tbodyRef.innerHTML = '';
}




function addAnswerRow() {
    // Recabar informacion del material, validar

    //crear lista de Preguntas
    options = `<option value="-1">Finalizar</option>`
    window.questions.forEach(question => {
        options += `<option value="${question.id}">${question.sentence}</option>`
    });

    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('answer-table').getElementsByTagName('tbody')[0];

    // Agregar fila (vacia)
    var newRow = tbodyRef.insertRow();

    // Construir celdas
    var rowHtmlContent = [
        `<input type="hidden" name="answer_id" value="0">`,
        `<td><input type="text" name="answer" class="input-group-sm form-control" style="text-align:left;" placeholder="Escribe tu respuesta"></td>`,
        `<td>
            <select name="next_question" class="form-control">
                ${options}
            </select>
        </td>`,
        `<td><button type="button" onclick="removeAnswerRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
    ].join('');

    // Añadir celdas a la fila
    newRow.innerHTML = rowHtmlContent;
}


function removeAnswerRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}


function fillQuestionForm(data) {
    $("#id").val(data.id);
    $("#sentence").val(data.sentence);
    data.first_question
        ? document.getElementById("true").selected = true
        : document.getElementById("false").selected = true;
    

    // Obtener la tabla (cuerpo)
    var tbodyRef = document.getElementById('answer-table').getElementsByTagName('tbody')[0];
    let answers = data.answers;
    for(var i = 0; i < answers.length; i++) {
        // Agregar fila (vacia)
        var newRow = tbodyRef.insertRow();
        
        //crear lista de Preguntas
        options = `<option value="-1">Finalizar</option>`
        window.questions.forEach(question => {
            options +=  question.id == answers[i].id_next_question
                        ? `<option value="${question.id}" selected="selected">${question.sentence}</option>`
                        : `<option value="${question.id}">${question.sentence}</option>`;
        });

        // Construir celdas
        var rowHtmlContent = [
            `<input type="hidden" name="answer_id" value="0">`,
            `<td><input type="text" name="answer" class="input-group-sm form-control" value="${answers[i].text}" placeholder="Escribe tu respuesta"></td>`,
            `<td>
                <select name="next_question" class="form-control">
                    ${options}
                </select>
            </td>`,
            `<td><button type="button" onclick="removeAnswerRow(this)" class="btn btn-outline-danger btn-sm">Quitar</button></td>`
        ].join('');

        // Añadir celdas a la fila
        newRow.innerHTML = rowHtmlContent;
    }
}

function cleanQuestionForm() {
    $("#id").val('');
    $("#sentence").val('');
    document.getElementById("false").selected = true;

    cleanAnswersTable();
}

function saveQuestion() {
    var error = false;
    var id = $("#id").val();
    var sentence = $('#sentence').val();
    var first_question = $('#first_question').val();
    var answers = getAnswers();
    console.log(first_question);

    !sentence.trim() && (error = true) && toastr.warning('La Pregunta es requerida.', 'Información incompleta!');
    

    answers.forEach(element => {
        if (!element.text.trim()) {
            error = true;
            return toastr.warning('El campo respuesta no puede estar vacio.', 'Información incompleta!');
        }
    });

    if(error) { return; }

    var data = {
        'sentence': sentence,
        'first_question': first_question,
        'answers': answers
    };

    console.log(data);

    if(id) {
        data['id'] = id;
        sendUpsertQuestionRequest(`/admin/questions/${id}`, 'put', data);
    } else {
        sendUpsertQuestionRequest('/admin/questions', 'post', data);
    }
}

function getAnswers() {
    var answers = [];
    var answer_ids = document.getElementsByName('answer_id');
    var texts = document.getElementsByName('answer');
    var next_questions = document.getElementsByName('next_question');

    for(var i = 0; i < answer_ids.length; i++) {
        answers.push({
            'id': answer_ids[i].value,
            'text': texts[i].value,
            'id_next_question': next_questions[i].value
        });
    }

    return answers;
}

function sendUpsertQuestionRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanQuestionForm();
            $("#addModal").modal('hide');
            loadQuestions();

            Swal.fire({
                title: result.data.sentence,
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