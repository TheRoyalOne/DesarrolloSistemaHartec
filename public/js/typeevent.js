
function operateFormatter(value, row, index, field){
    return[
        '<button class="btn btn-warning btn-edit" ><i class="fa fa-pencil"></i> </button>'
    ].join('')
}
function operateFormatter2(value, row, index, field){
    return[
        '<button class="btn btn-danger btn-delete"><i class="fa fa-trash"></i> </button>'
    ].join('')
}
var operateEvents2= {
    'click .btn-delete': function(e, value, row, index){
        var route = "typeevent/"+row.id;
        $.ajax({
            url:route,
            type: 'delete',
            dataType: 'json',
            success:function(result){
                window.location.reload("true");
            }
        })
    }
}

var operateEvents = {
    'click .btn-edit': function(e, value, row ,index){
        var route = baseURL + '/Getinfo/'+ row.id;
        updateid=row.id;
        $.ajax({
            url:route,
            type:'get',
            dataType:'json',
            success:function(result){
                $('#id').val(result.data.id);
                $("#name1").val(result.data.name);
                $("#description1").val(result.data.description);
                $("#editModal").modal('show');

            }
        })
    }
}
var updateid = 0;
function updateevent(){
    var name = $("#name1").val();
    var description = $("#description1").val();
    var route = baseURL + "/actualizar";
    if(name == null || name == "")
    {
        alert("El nombre es requerido");
        return;
    }
    var data = {
        'id':updateid,
        'name':name,
        'description':description
    };
    $.ajax({
        url:route,
        type:'post',
        data:data,
        dataType:'json',
        success:function(result){
            alert(result.message);
            window.location.reload(true);
            $("#editModal").modal('hide');
        }
    })
}
function saveevent(){
    var name = $("#name").val();
    var description = $("#description").val();
    var route = "typeevent";
    if(name == null || name == "")
    {
        alert("El nombre es requerido");
        return;
    }
    var data = {
        'name':name,
        'description':description
    };
    $.ajax({
        url:route,
        data:data,
        type:'post',
        dataType:'json',
        success:function(result){
            alert(result.message);
            window.location.reload(true);
            $("#addModal").close();
        }
    })
}