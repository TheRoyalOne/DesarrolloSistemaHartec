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
        var route = "adoptionevents/"+row.id;
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
    'click .btn-edit': function(e, value, row, index)
    {
        var route = baseURL + '/Getinfo/'+row.id;
        updateid=row.id;
        $.ajax({
            url:route,
            type:'get',
            dataType: 'json',
            success:function(result)
            {
                $('#id').val(result.data.id);
                $("#name1").val(result.data.name);
                $("#prefix_code1").val(result.data.prefix_code);
                $("#trees1").val(result.data.trees);
                $("#recovery_fee1").val(result.data.recovery_fee);
                $("#description1").val(result.data.description);
                $("#editModal").modal('show');

            }
        })
    }
}
var updateid = 0;
function updateadaption(){
    var name = $("#name1").val();
    var prefix_code = $("#prefix_code1").val();
    var trees = $("#trees1").val();
    var recovery_fee = $("#recovery_fee1").val();
    var description = $("#description1").val();
    var route = baseURL + "/actualizar";
    if(name == null || name == "")
    {
        alert("El Nombre es requerido");
        return;
    }
    if(prefix_code == null || prefix_code=="")
    {
        alert("El Código es requerido");
        return;
    }
    if(trees == null || trees == "")
    {
        alert("El número de árboles es requerido");
        return;
    }
    if(recovery_fee == null || recovery_fee=="")
    {
        alert("La Cuota es requerida");
        return;
    }
    var data = {
        'id':updateid,
        'name':name,
        'prefix_code':prefix_code,
        'trees':trees,
        'recovery_fee':recovery_fee,
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

function saveadoption(){
    var name = $("#name").val();
    var prefix_code = $("#prefix_code").val();
    var trees =$("#trees").val();
    var recovery_fee = $("#recovery_fee").val();
    var description = $("#description").val();
    var route = "adoptionevents";
    if(name == null || name == "")
    {
        alert("El Nombre es requerido");
        return;
    }
    if(prefix_code == null || prefix_code=="")
    {
        alert("El Código es requerido");
        return;
    }
    if(recovery_fee == null || recovery_fee=="")
    {
        alert("La Cuota es requerida");
        return;
    }
    var data = {
        'name':name,
        'prefix_code':prefix_code,
        'trees':trees,
        'recovery_fee':recovery_fee,
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