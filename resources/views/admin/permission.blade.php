@extends('layouts.app')
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Catálogo de Permisos</h3>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('nameProfileClik','Perfil:') !!}
                    {!! Form::select('nameProfileClik',$prof,null,['class'=>'form-control','placeholder'=>'Seleccione una opcion']) !!}

                </div>
            </div>
            <div class="col-md-8">
                <p style="margin-top: 35px; color:red;" >Recuerde seleccionar un perfil para poder modificar los permisos**</p>
            </div>
        </div>

        <!-- ACORDION PERMISOS -->
        <div class="panel-group" id="accordion">
            @foreach($padre as $padres )

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$padres->id}}" data-padre="{{$padre = $padres->id}}">{{$padres->section}}</a>
                        </h4>
                    </div>
                    <div id="collapse{{$padres->id}}" class="panel-collapse collapse ">
                        <div class="panel-body">

                            <div class="table-responsive" style="margin: 10px auto; max-width: 950px">
                                <table class="table table-striped table-hover text-center">
                                    <thead >
                                    <th class="text-center">Modulo</th>
                                    <th class="text-center">Ver</th>
                                    <th class="text-center">Agregar</th>
                                    <th class="text-center">Editar</th>
                                    <th class="text-center">Eliminar</th>
                                    </thead>
                                    <tbody>
                                    @foreach($hijos as $hijo )

                                        @if($hijo->reference == $padres->id)

                                            <tr data-hijo="{{$hijo->id}}" data-reference="{{$hijo->reference}}">
                                                <td class="text-left">
                                                    <strong>
                                                        {{$hijo->section}}
                                                    </strong>
                                                </td>
                                                <!--VALIDAR-->
                                                @if($hijo->hijo==0)
                                                    <td>
                                                        <input type="checkbox"     id="view_{{$hijo->id}}" disabled="disabled" class="view_id">
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveAdd==1)
                                                            <input type="checkbox" id="add_{{$hijo->id}}" disabled="disabled" class="add_id">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveUpdate==1)
                                                            <input type="checkbox" id="update_{{$hijo->id}}" disabled="disabled" class="update_id">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hijo->haveDelete==1)
                                                            <input type="checkbox" id="delete_{{$hijo->id}}" disabled="disabled" class="delete_id">
                                                        @endif
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                            </tr>
                                            @endif
                                            <!--/VALIDAR-->

                                            @foreach($hijos as $hijo2 )

                                                @if($hijo2->reference == $hijo->id)
                                                    <tr data-hijo="{{$hijo2->id}}" data-reference="{{$hijo2->reference}}">
                                                        <td class="text-left">
                                                                   <span style="padding-left: 20px;">
                                                                       {{$hijo2->section}}
                                                                   </span>
                                                        </td>
                                                        <td >
                                                            <input type="checkbox" id="view_{{$hijo2->id}}" disabled="disabled" class="view_id" >
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveAdd==1)
                                                                <input type="checkbox" id="add_{{$hijo2->id}}" disabled="disabled"  class="add_id" >
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveUpdate==1)
                                                                <input type="checkbox" id="update_{{$hijo2->id}}" disabled="disabled" class="update_id">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($hijo2->haveDelete==1)
                                                                <input type="checkbox" id="delete_{{$hijo2->id}}" disabled="disabled" class="delete_id">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif

                                            @endforeach

                                        @endif

                                    @endforeach

                                    </tbody>
                                </table>
                                {!! Form::open(['route'=> ['admin.permission.update_store',':USER_ID'],'method'=>'POST', 'id'=>'form-update_store']) !!}

                                {{-- {!! Form::close() !!} --}}

                            </div>
                        </div>
                    </div>
                </div>

            @endforeach 

        </div>
        <!-- /ACORDION PERMISOS -->

    </div>
</div>
@endsection
@section('js')
<script>
    var hash = window.location.hash;
    // alert(hash);
    if(hash==""){
        $('.nav-tabs a[href="#profile"]').tab('show');
    }else{
        $('.nav-tabs a[href="#users"]').tab('show');
    }
    // $.fn.editable.defaults.mode = 'inline';
    // $.fn.editable.defaults.ajaxOptions = {type:'PUT'};

    //******************************************************************************************* PERFILES TAB---------------------------------------------------------------------------------------------------


    $('#nameProfileClik').change(function () {
        // alert("entre");
        var id = $("#nameProfileClik").val();
        // alert(id);
        if(id == ""){
            $('input:checkbox').prop('disabled', true);//bloquear
            $('input:checkbox').prop("checked", false);//quitar marca
        }else{
            $('input:checkbox').prop('disabled', false);//desbloquear
            $('input:checkbox').prop("checked", false);//quitar marcas

            var route = "{{url('admin/permission/')}}/"+id+"/edit";
            $.get(route, function(data){
                if(data.length>0){
                    $.each( data, function( index, data ){
                        if(data.view == 0){//quitar marcas y bloquear add,update,delete
                            $("#add_"+data.section_id).prop("checked", false);
                            if (data.add == 1) {
                                $("#add_" + data.section_id).prop("checked", true);
                            }else{$("#add_" + data.section_id).prop("checked", false);}
                            if (data.update == 1) {
                                $("#update_" + data.section_id).prop("checked", true);
                            }else{ $("#update_" + data.section_id).prop("checked", false);}

                            if (data.delete == 1) {
                                $("#delete_" + data.section_id).prop("checked", true);
                            }else{ $("#delete_" + data.section_id).prop("checked", false);}
                        } else {
                            $("#view_" + data.section_id).prop("checked", true);//poner su marca
                            if (data.add == 1) {
                                $("#add_" + data.section_id).prop("checked", true);
                            }else{$("#add_" + data.section_id).prop("checked", false);}
                            if (data.update == 1) {
                                $("#update_" + data.section_id).prop("checked", true);
                            }else{ $("#update_" + data.section_id).prop("checked", false);}

                            if (data.delete == 1) {
                                $("#delete_" + data.section_id).prop("checked", true);
                            }else{ $("#delete_" + data.section_id).prop("checked", false);}

                        }//fin else de vista es 1
                    });//fin each recorrido
                }else{
                    $('input:checkbox').prop("checked", false);//quitar marca
                }
            });// fin get data
        }//fin else- area logica
        $.ajax({

        })
    });
    //ULTIMO VALOR DE LAS RUTAS
    // VER = 0
    // AGREGAR = 1
    // EDITAR = 2
    // ELIMINAR = 3

    $(".view_id").click(function () {
        var id = $("#nameProfileClik").val();
        var row = $(this).parents('tr');
        var section = $(row).attr('data-hijo');
        var form = $('#form-update_store');
        var row_ = $(this).parents('tr');
        var id_reference = $(row_).attr('data-reference');
        console.log(form);
        if(id_reference==undefined){
             id_reference=0;
        }
        var route = form.attr('action').replace(':USER_ID',id+"/"+section+"/"+0+"/"+id_reference);
        // aler(route);
        console.log(route);
        if(id == ""){}else{
         $.get(route, function(result){
            }).fail(function() {
             alert("Advertencia No se pudo procesar el permiso de  Ver.");
         });
        }
    });


    $(".add_id").click(function () {
        var id = $("#nameProfileClik").val();
        var row = $(this).parents('tr');
        // console.log(row);
        var section = $(row).attr('data-hijo');
        console.log(section);
        var form = $('#form-update_store');
        var row_ = $(this).parents('tr');
        var id_reference = $(row_).attr('data-reference');
        console.log(id_reference);
        if(id_reference== undefined){
             id_reference=0;
        }
        var route = form.attr('action').replace(':USER_ID',id+"/"+section+"/"+1+"/"+id_reference);
        if(id == ""){}else{
            $.get(route, function(data){
                if(data.length>0){
                  $("#view_" + data[0]).prop("checked", true);//poner su marca
                }
            }).fail(function() {
                alert("Advertencia No se pudo procesar el permiso de Agregar.");
            });
        }
    });

    $(".update_id").click(function () {
        var id = $("#nameProfileClik").val();
        var row = $(this).parents('tr');
        var section = row.data('hijo');
        var form = $('#form-update_store');
        var row_ = $(this).parents('tr');
        var id_reference = row_.data('reference');
        if(id_reference==undefined){
             id_reference=0;
        }
        var route = form.attr('action').replace(':USER_ID',id+"/"+section+"/"+2+"/"+id_reference);
        if(id == ""){}else{
            $.get(route, function(data){
                if(data.length>0){
                    $("#view_" + data[0]).prop("checked", true);//poner su marca
                }
            }).fail(function() {
                alert("Advertencia No se pudo procesar el permiso de Editar.");
            });
        }

    });


    $(".delete_id").click(function () {
        var id = $("#nameProfileClik").val();
        var row = $(this).parents('tr');
        var section = row.data('hijo');
        var form = $('#form-update_store');
        var row_ = $(this).parents('tr');
        var id_reference = row_.data('reference');
        if(id_reference==undefined){
             id_reference=0;
        }
        var route = form.attr('action').replace(':USER_ID',id+"/"+section+"/"+3+"/"+id_reference);
        if(id == ""){ console.log("perfil vacio");}else{
            $.get(route, function(data){
                if(data.length>0){
                    $("#view_" + data[0]).prop("checked", true);//poner su marca
                }
            }).fail(function() {
                alert("Advertencia No se pudo procesar el permiso de Editar.");
            });
        }

    });

    $('.btn-delete-profile').click(function (e) {
        e.preventDefault();
        var row = $(this).parents('tr');
        var id = row.data('id');
        var form = $('#form-delete-profile');

        alertify.confirm("Advertencia","¿Está seguro que desea realizar esta operación?, se borrará todo Usuario que contenga este perfil.",
                function(){
                    //  alertify.success('Ok');
                    var url = form.attr('action').replace(':USER_ID',id);
                    var data = form.serialize();
                    row.fadeOut();

                    $.post(url,data,function (result) {
                        $('#msjAlterno').html('<div class="alert alert-success mensajesAll"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result+'</div>');
                    }).fail(function () {
                        row.fadeIn();
                        row.show();
                        $('#msjAlterno').html('<div class="alert alert-danger mensajesAll"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> El Perfil no pudo ser eliminado! </div>');

                    });
                },
                function(){
                    //  alertify.error('Cancel');
                });


    });
    $('#myModalProfile').on('shown.bs.modal', function() {
        $('#nameProfile').focus();
    });

    //******************************************************************************************* USUARIOS TAB---------------------------------------------------------------------------------------------------
    $('#myModal').on('shown.bs.modal', function() {
        $('#username').focus();
    })
    $('#myModalEdit').on('shown.bs.modal', function() {
        $('#username1').focus();
    })
    $('.saveUser').click(function (e) {
        e.preventDefault();
        var form = $('#form-create');
        var url = form.attr('action');
        var data = form.serialize();
        $.post(url,data,function (result) {
            if(result.success){
                var name = result.data.name;

                $('#myModal').modal('hide');
                $('#msjAlterno').html('<div class="alert alert-success mensajesAll"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Usuario '+name+' creado exitosamente!</div>');
                $("#username").val("");
                $("#name").val("");
                $("#email").val("");
                $("#password").val("");
                $("#bankAccount").val("");
                $("#bank_id").val("");
                $("#profile_id").val("");

                var url= window.location.href;

                window.location.href = url+"#users";
                window.location.reload(true);
            }
        }).fail(function (data) {
            setTimeout(function() {$(".mensajesAllValid").fadeOut(1500);},5000);
            $('#myModal').modal('hide');

            var errors = '<div class="alert alert-danger mensajesAllValid"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> ';
            for(datos in data.responseJSON){
                errors += data.responseJSON[datos] + '<br>';
            }
            errors +='</div>';

            $('#msjAlterno').show().html(errors);
        });
    });



    $('.btn-delete').click(function (e) {

        e.preventDefault();
        var row = $(this).parents('tr');
        var id = row.data('id');
        var form = $('#form-delete');
        var url = form.attr('action').replace(':USER_ID',id);
        var data = form.serialize();
        row.fadeOut();

        $.post(url,data,function (result) {
            $('#msjAlterno').html('<div class="alert alert-success mensajesAll"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result+'</div>');
        }).fail(function () {
            row.fadeIn();
            row.show();
            $('#msjAlterno').html('<div class="alert alert-danger mensajesAll"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> El Usuario no pudo ser eliminado! </div>');

        });

    });
    $('.status').click(function (e) {
        e.preventDefault();
        var row = $(this).parents('tr');
        var id =row.data('id');
        var form = $('#form-update');
        var url = form.attr('action').replace(':USER_ID',id);
        var data = form.serialize();

        $.post(url,data,function (result) {
            if(result.statusUser=="activo"){
                $('#'+result.id).html('<i class="fa fa-check-circle-o fa-2x" aria-hidden="true"></i>');
            }else if(result.statusUser=="inactivo"){
                $('#'+result.id).html('<i class="fa fa-circle-o fa-2x" aria-hidden="true"></i>');
            }

        }).fail(function () {
            $('#msjAlterno').html('<div class="alert alert-danger mensajesAll"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> El Estado no pudo ser editado! </div>');

        });
    });

    var dataa=[];

    var Views = function(id) {
        var route = "{{url('admin/users/')}}/"+id+"/edit";

        $.get( route, function( data ) {
            $("#id").val(data.id);
            $("#username1").val(data.username);
            $("#name1").val(data.name);
            $("#last_name_1").val(data.last_name1);
            $("#last_name_2").val(data.last_name2);
            $("#email1").val(data.email);
            $("#bankAccount1").val(data.bankAccount);
            $("#bank_id1").val(data.bank_id);
            $("#profile_id1").val(data.profile_id);
            $('#myModalEdit').modal('show');
        });
    }
    $('#actualizar').click(function () {
        var id=  $("#id").val();
        var token =  $("#token").val();
        var route = "{{url('admin/users/')}}/"+id+"";
        var formId = '#form';
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.ajax({
            url: route,
            type:'put',
            data:  $(formId).serialize(),
            dataType: 'json',
            success:function (result) {
                if(result.success){
                    $('#myModalEdit').modal('hide');
                    $('#msjAlterno').html('<div class="alert alert-success mensajesAllSave"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Usuario editado exitosamente!</div>');
                    setTimeout(function() {$(".mensajesAllSave").fadeOut(1500);},5000);
                    $("#UserName_" + result.info.id).text(result.info.username);
                    $("#Name_" + result.info.id).text(result.info.name);
                    $("#Email_" + result.info.id).text(result.info.email);
                    $("#Profile_" + result.info.id).text(result.perfil);
                }
            },error:function (data) {
                setTimeout(function() {$(".mensajesAllValid").fadeOut(1500);},5000);
                $('#myModalEdit').modal('hide');

                var errors = '<div class="alert alert-danger mensajesAllValid"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> ';
                for(datos in data.responseJSON){
                    errors += data.responseJSON[datos] + '<br>';
                }
                errors +='</div>';

                $('#msjAlterno').show().html(errors);
            }
        });
    });
</script>

@endsection