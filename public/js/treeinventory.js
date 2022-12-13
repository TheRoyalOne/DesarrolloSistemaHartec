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
        var route = "entries/"+row.id;
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
        console.log(route);
        updateid=row.id;
        $.ajax({
            url:route,
            type:'get',
            dataType:'json',
            success:function(result){
                $("#id_nurserie_edit").val(result.data.id_nurserie);
                $("#id_species_edit").val(result.data.id_species);
                $("#amount_edit").val(result.data.amount);
                $("#age_edit").val(result.data.age);
                $("#edit_Entrie").modal('show');
            }
        })
    }
}
function saveentrance(form)
{
    const formData = new FormData(form);
    const requestInit = {
        method: 'POST',
        body: formData,
        headers: {
            "_token" : $("meta[name='csrf-token']").attr("content"),
            "X-CSRF-TOKEN" : $("meta[name='csrf-token']").attr("content"),
        }
    };
    fetch("entries",requestInit)
        .then(function(response) {
            
            window.location.reload(true);
            $("#add_Entrie").modal('hide');
        })
    .catch(function (error){
        console.log(error);
    })
}

function updateentrance(form)
{
    
}