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

    loadUsers();
}

function loadUsers() {
    $.ajax({
        url: '/admin/users',
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
        var route = `/admin/users/${row.id}`;

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(result)
            {
                user = result.data;
                fillUserForm(user);
                $("#addModal").modal('show');
            },
            error(err) {
                Swal.fire({
                    allowOutsideClick: true,
                    title: 'Error al cargar información del Usuario.',
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
            text: `Se eliminará el usuario ${row.username}.`,
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then(resp => {
            if (resp.value) {
                $.ajax({
                    url: `/admin/users/${row.id}`,
                    type: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        loadUsers();

                        Swal.fire({
                            title: '',
                            text: 'Se eliminó el usuario correctamente.',
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

function fillUserForm(data) {
    $("#id").val(data.id);
    $("#username").val(data.username);
    $("#name").val(data.name);
    $("#lastname").val(data.lastname);
    $("#lastname2").val(data.lastname2);
    $("#email").val(data.email);
    $("#password").val(data.password);
    $("#profile_id").val(data.profile_id);
    $("#cellphone").val(data.cellphone);
    $("#job").val(data.job);
    $("#educative_institution_id").val(data.educative_institution_id);
}

function cleanUserForm() {
    $("#id").val('');
    $("#username").val('');
    $("#name").val('');
    $("#lastname").val('');
    $("#lastname2").val('');
    $("#email").val('');
    $("#password").val('');
    $("#profile_id").val('');
    $("#cellphone").val('');
    $("#job").val('');
    $("#educative_institution_id").val('');
}

function saveUser(){
    var error = false;
    var id = $("#id").val();
    var username = $("#username").val();
    var name = $("#name").val();
    var lastname = $("#lastname").val();
    var lastname2 = $("#lastname2").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var profile_id = $("#profile_id").val();
    var cellphone = $("#cellphone").val();
    var job = $("#job").val();
    var educative_institution_id = $("#educative_institution_id").val();

    !username.trim() && (error = true) && toastr.warning('El Nombre de Usuario es requerido.', 'Información incompleta!');
    !password.trim() && (error = true) && toastr.warning('El Password es requerido.', 'Información incompleta!');
    !name.trim() && (error = true) && toastr.warning('El Nombre es requerido.', 'Información incompleta!');
    !profile_id && (error = true) && toastr.warning('El Perfil es requerido.', 'Información incompleta!');

    if(error) {
        return;
    }
    
    var data = {
        'username': username,
        'name': name,
        'lastname': lastname,
        'lastname2': lastname2,
        'email': email,
        'password': password,
        'profile_id': profile_id,
        'cellphone': cellphone,
        'job': job,
        'educative_institution_id': educative_institution_id,
    };

    if(id) {
        data['id'] = id;
        sendUpsertAnswerRequest(`/admin/users/${id}`, 'put', data);
    } else {
        sendUpsertAnswerRequest('/admin/users', 'post', data);
    }
}

function sendUpsertAnswerRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success: function(result) {
            cleanUserForm();
            $("#addModal").modal('hide');
            loadUsers();

            Swal.fire({
                title: result.data.username,
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

