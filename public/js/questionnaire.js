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
    
    window.last = '#addModal';
    loadQuestionaires();
}

function loadQuestionaires() {
    $.ajax({
        url: '/admin/questionnaire',
        type: 'get',
        dataType: 'json',
        success: function(result) {
            //$('#table').bootstrapTable('load', result.data);
            getTotales();
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
        var route = `/admin/questionnaire/${row.id}`;
        cleanCuestionnaire();

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
                    url: `/admin/questionnaire/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadQuestionaires();

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

    populateMaterialTable(data.leaving_material);
}

function cleanForm() {
    $('#id').val('');
    $('#workshop_id').val('');
    $('#technical_user_id').val('');
    $('#leaving_date').val('');

    cleanCuestionnaire();
}

function toQuestionnaire() {
    //cierra modal principal y abre modal del cuestionario
    
}

function saveQuestionnaire() {
    let s = document.getElementById("Sponsor_id");
    let sponsor = s.options[s.selectedIndex];

    var data = {
        'id_sponsor': sponsor.value,
        'id_buyer': window.buyerId,
        'id_event': window.eventId,
        'all_questions': window.stackQuestions //obtener el ancla
        //id_question_key
    }

    console.log(data);

    //faker
    let total = document.getElementById("total");
    // console.log(total, total.text, total.innerHTML);
    total.innerHTML = parseInt(total.innerHTML) + 1;
    window.last = '#addModal';
    //guarda la lista de preguntas y respuestas
    //guarda la ancla a la primer pregunta
    sendUpsertQuestionnaireRequest('/admin/questionnaire', 'post', data);
    //actualizar lista de encuestas totales
    getTotales();
}


function cleanCuestionnaire() {
    //document.getElementById('Sponsor_id').getElementsByTagName('option')[0].selected = 'selected';
    //document.getElementById('question_idx').getElementsByTagName('option')[0].selected = 'selected';
    //document.getElementById('modal_prime').disabled = 'disabled';
}

function lastQuestion(btn) {
    btn.setAttribute('data-target', window.last);
    console.log()
}

function sendUpsertQuestionnaireRequest(url, type, data) {
    $.ajax({
        url: url,   //admin/questionnaire
        type: type, //post
        data: data,
        dataType: 'json',
        success: function(result) {
            console.log(result);
            cleanForm();
            //$("#addModal").modal('hide');
            loadQuestionaires();
        },
        error: function(err) {
            console.log('error: ', err);
            err_resp = err.responseJSON;

            Swal.fire({
                title: 'Error al guardar.',
                text: err_resp.user_message,
                icon: 'error',
                timer: 2000
            });
        }
    });
}

function primeModal(select) {
    // verificar que el patrocinador y la pregunta se hayan seleccionado
    let sponsor = document.getElementById('Sponsor_id');
    let question = document.getElementById('question_idx');

    if(sponsor.selectedIndex != 0 && question.selectedIndex != 0) {
        document.getElementById("modal_prime").disabled = false;
        window.question_id = question.value;
    }
}

function getRandomUser() {
    //resear antes de cargar
    document.getElementById("event").value = 'Nombre del evento';
    document.getElementById("buyer").value = 'Nombre de la persona';
    document.getElementById("phone").value = '33-3333-3333';
    
    //desactivar btn siguiente
    let btn_next = document.getElementById("modal_questions");
    btn_next.disabled = true;

    // guardar el patrocinador
    let sponsors = document.getElementById("Sponsor_id");
    let sponsor = sponsors.options[sponsors.selectedIndex]; // value, text
    document.getElementById("sponsor_name").value = sponsor.text;

    /* obtener evento y nombre del beneficiario de forma aleatoria */
    // sponsors.value // id
    rand(sponsors.value);


    console.log("random");
    window.last = '#vista_usuario';
}

function rand(id) {
    var route = `/admin/questionnaire/${id}`;

    $.ajax({
        url: route,
        type: 'get',
        dataType: 'json',
        success: function(result) {
            let events = result.data;
            let finished = false;

            while(true) {
                if(finished || events.length == 0) { break; }
                //busco un evento aleatoriamente
                let idx = Math.floor(Math.random() * events.length);
                let event = events[idx];
                //verifico que ese evento tenga beneficiarios
                if(event.buyers.length == 0) {
                    //eliminar el evento
                    // delete events[idx];
                    events.splice(idx, 1);
                    continue;
                }
                //mostrar los datos en pantalla
                document.getElementById("event").value = event.event_name;
                buyerId = Math.floor(Math.random() * event.buyers.length);
                document.getElementById("buyer").value = event.buyers[buyerId].buyer_name;
                document.getElementById("phone").value = event.buyers[buyerId].buyer_phone;
                //guardar los id
                window.buyerId = event.buyers[buyerId].buyer_id;
                window.eventId = event.event_id;
                finished = true;
            }

            if(events.length == 0) {
                Swal.fire({
                    backdrop: true,
                    allowOutsideClick: true,
                    title: 'Error al cargar información.',
                    text: `parece que no hay beneficiarios para este patrocinador`,
                    icon: 'error'
                });
            } else {
                //activar btn siguiente
                let btn_next = document.getElementById("modal_questions");
                btn_next.disabled = false;
            }
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



function primeQuestion() {
    let btn_next = document.getElementById("modal_questions");
    btn_next.setAttribute('data-target', '#questionnarie-'+window.question_id);
    btn_next.disabled = false;
    window.actual = window.question_id;
    window.back = "#vista_usuario";
    window.stackQuestions = [ ];
}

function initQuestion(select) {
    // resetear las respuestas
    resetQuestion();
    
    primeQuestion();
    btn_back = document.getElementById('return-'+window.actual);
    btn_back.setAttribute('data-target', window.back);
    window.last = '#questionnarie-'+window.question_id;
}

function resetQuestion() {
    let answers = document.getElementsByClassName('input-answer');
    answers.forEach(function(answer) { answer.checked = false; });

    let nexts = document.getElementsByClassName('btn-next');
    nexts.forEach(function(next) { next.disabled = true; });
}

function nextQuestion(select){
    let id = select.name.split('-')[1];
    let next = select.getAttribute('data-next');
    let answer = select.getAttribute('data-answer');
    let btn_next = document.getElementById("modal-"+id);
    btn_next.setAttribute('data-target', '#questionnarie-'+next);
    btn_next.disabled = false;
    if(next == -1) {
        btn_next.innerHTML = "Finalizar";
    } else {
        btn_next.innerHTML = "Siguiente";
    }
    window.actual = next;
    window.answer = answer;
    window.back = id;

}


function stackQuestion(select) {
    //obtener respuesta
    let stack = {
        "id_question": window.back,
        "id_answer": window.answer
    }
    //apilar respuesta
    window.stackQuestions.push(stack);

    //si la pregunta es la ultima terminar cuestionario
    if (window.actual == -1) {
        saveQuestionnaire();
        return;
    }
    //sino obtener la siguiente pregunta
    btn_back = document.getElementById('return-'+window.actual);

    btn_back.setAttribute('data-target', '#questionnarie-'+window.back);

    window.last = '#questionnarie-'+window.actual;
}


function popQuestion() {
    window.stackQuestions.pop();
    resetQuestion();
}


/* **************** ENCUESTAS **************** */
function getTotales() {
    let sponsor = document.getElementById('Sponsor_report');
    let quest   = document.getElementById('Questionnaire_report');
    let f_i     = document.getElementById('f_inicio');
    let f_f     = document.getElementById('f_fin');

    let now = new Date();
    console.log()
    f_i_s = f_i.value == '' ? '2000-01-01 00:00:00' : f_i.value + " 00:00:00";
    f_f_s = f_f.value == '' ? now.toISOString().split('T')[0] + " 23:59:59" : f_f.value + " 23:59:59";

    window.rangeDate = f_i_s.split(' ')[0] + " - " + f_f_s.split(' ')[0];

    //obtener el total de encuestas realizadas
    s = isNaN(sponsor.value) ? 1.1 : sponsor.value;

    getTotals(-s, quest.value, f_i_s, f_f_s);
}


function getTotals(id, quest, fi, f_f) {
    var route = `/admin/questionnaire/${id},${quest},${fi},${f_f}`;
    console.log(route);
    $.ajax({
        url: route,
        type: 'get',
        dataType: 'json',
        success: function(result) {            
            if(quest == -1) {
                //obetener el numero de encuestas
                document.getElementById('total').value = result.data[0].length;
                //rellenar reporte
                document.getElementById('report').innerHTML = 'debe seleccionar una encuesta';
            } else {
                let count = 0;
                result.data[0].forEach(function(row) {
                    if(row.question_key == quest) { count++; }
                });
                //obetener el numero de encuestas
                document.getElementById('total').value = count;
                
                //carga y crea las encuestas
                create(result.data[1]);
                //rellenar de resultados la encuesta
                //idPregunta-idRespuesta
                populate(result.data[0]);
            }
        },
        error(err) {
            console.log(err);
            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar información de la Salida del Material.',
                text: `${err.user_message}`,
                icon: 'error'
            });
        }
    });

}


function create(questions) {
    //rellenar reporte
    let parent = document.getElementById("report");
    let sponsors = document.getElementById("Sponsor_report");
    let sponsor = sponsors.options[sponsors.selectedIndex]; // value, text

    parent.innerHTML = '';
    parent.innerHTML += `<img class="pdf-logo" src="../../assets/img/extralogocolor.png"> `;
    parent.innerHTML += `<div class="question-header">
                            <span>Periodo: <span>${window.rangeDate}</span></span>
                            <span>Encuestados: <span>${document.getElementById('total').value}</span></span>
                            <span>Patrocinador: <span>${sponsor.text}</span></span>
                        </div>`;
                        
    //crear lista de todas las respuestas con porcentajes para la grafica
    window.pie = {};
    answers = [];
    texts = [];
    questions.forEach(question => {
        parent.innerHTML += `
            <div class="question">${question.text}</div>
        `;
        question.answers.forEach(answer => {
            parent.innerHTML += `
                <div class="answer" style="display: none;">${answer.text}: <div id="${question.question}-${answer.id}" class="answer-result">0</div></div>
            `;
            answers.push(answer.id);
            texts.push(answer.text);
        });
        //crear grafica de pastel
        parent.innerHTML += `<canvas id="pie-${question.question}" style="max-height: 260px;"></canvas>`;

        window.pie[question.question] = {
            id: question.question,
            answers: answers,
            texts: texts
        };
        answers = [];
        texts = [];
    });
}


function populate(answers) {
    anchors = [];
    answers.forEach(function(answer) { anchors.push(answer.anchor); });
    var route = `/admin/questionnaire/-1,-1,${anchors}`;
    $.ajax({
        url: route,
        type: 'get',
        dataType: 'json',
        success: function(result) {
            result.data.forEach(function(response) {
                response.forEach(function(answer) {
                    let a = document.getElementById(answer.id_question + '-' + answer.id_answer);
                    try {
                        //contar elementos
                        a.textContent++;
                    } catch (e) {
                        //crear
                        // answers[answer.id_question][answer.id_answer]++;
                    }
                });
            });

            console.log(answers);

            jQuery.each(window.pie, function(i, pie) {
                pie.answers.forEach(function(answer, index) {
                    let v = document.getElementById(pie.id + '-' + answer);
                    pie.answers[index] = v.textContent;
                });
                createGraphics(`pie-${pie.id}`, pie.texts, pie.answers);
                //$("#" + i).append(document.createTextNode(" - " + val));
              });

            Swal.fire({
                icon: 'success',
                title: 'Genial',
                text: 'Informacion cargada correctamente.',
                showConfirmButton: false,
                timer: 600
              });
        },
        error(err) {
            console.log(err);
            Swal.fire({
                allowOutsideClick: true,
                title: 'Error al cargar información de la Salida del Material.',
                text: `${err.user_message}`,
                icon: 'error'
            });
        }
    });

}


function createGraphics(id, labels,data, bgc) {
    let pieData = {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                "#269B09",
                "#B26D1C",
                "#919191",
                "#4EADEB",
                "#4EADEB",
                "#4EADEB",
                "#4EADEB",
                "#4EADEB",
                "#4EADEB",
                "#4EADEB",
            ],
            hoverOffset: 4,
        }],
    };

    let ctx = document.getElementById(id);

    let pieCtx = ctx.getContext('2d');
  
    let myPieChart = new Chart(pieCtx, {
        /* IMPORTANTE: cargamos el complemento */
        plugins: [ChartDataLabels],
        type: 'pie',
        data: pieData,
        options: {
            plugins: {
                datalabels: {
                /* anchor puede ser "start", "center" o "end" */
                anchor: "center",
                /* Podemos modificar el texto a mostrar */
                formatter: (dato) => dato + " ",
                /* Color del texto */
                color: "#323232",
                /* Formato de la fuente */
                font: {
                    family: '"Open Sans", sans-serif',
                    size: "26",
                    weight: "bold",
                },
                /* Formato de la caja contenedora */
                //padding: "4",
                //borderWidth: 2,
                //borderColor: "darkblue",
                //borderRadius: 8,
                //backgroundColor: "lightblue"
                }
            }
        }
    });

}

// function printPdf_1() {
//     var divContents = document.getElementById('report').innerHTML;
//     const printWindow = window.open(' ', '_blank', `width=${window.innerWidth},height=${window.innerHeight}`);
//     printWindow.onload = function(){
//         console.log(divContents);
//         printWindow.document.body.innerHTML = divContents;
//     }
// }

// function printPdf_2() {
//     var element = document.getElementById('report');
//     console.log(element);
//     var opt = {
//         margin:       1.5,
//         filename:     'Reporte de supervivencia.pdf',
//         image:        { type: 'jpeg', quality: 0.98 },
//         html2canvas:  { scale: 2 },
//         jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
//     };

//     // New Promise-based usage:
//     html2pdf().set(opt).from(element).save();
// }

function printPdf() {
    window.html2canvas = html2canvas;
    window.jsPDF = window.jspdf.jsPDF;

    html2canvas(document.querySelector("#report"),{
        allowTaint: false,
        useCORS: false,
        sclae: 1
    }).then(canvas => {
        //document.body.appendChild(canvas)
        var img = canvas.toDataURL("image/png");
        var report = new jsPDF();
        report.addImage(img,'PNG',10000/window.innerWidth,15,window.innerWidth/7.75,window.innerHeight/2.5);
        report.save("Reporte de superviviencia");
    });
}