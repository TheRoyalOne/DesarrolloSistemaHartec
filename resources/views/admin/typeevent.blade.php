@extends('layouts.app')
@section('content')
@include('admin.typeeventmodalEdit')
<div class="panel-heading">
    <h3 class="panel-title">Tipos de Evento</h3>   
</div>
<table
  class="table table-striped"
  id="table"
  data-id-field="id"
  data-filter-control="true"
  data-url="typeevent/show" 
  data-show-toggle="false"
  data-buttons-class="primary"
  data-buttons="buttons"
  data-show-search-clear-button="false"
  data-buttons-align="center"
  data-show-footer="true">
  <thead>
    <tr>
      <th data-field="id" data-filter-control="input">#</th>
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="description" data-filter-control="input">Descripción</th>
      {{-- <th data-field="lastname2" data-filter-control="input">Apellido Materno</th> --}}
      {{-- <th data-field="email" data-filter-control="input">Correo electrónico</th> --}}
      {{-- <th data-field="username" data-filter-control="input">Usuario</th> --}}
      {{-- <th data-field="lastname2" data-filter-control="input">Contraseña</th> --}}
      {{-- <th data-field="job" data-filter-control="input">Puesto</th> --}}
      {{-- <th data-field="profile_id" data-filter-control="input">Perfil</th> --}}
      {{-- <th data-field="educational_institution_id" data-filter-control="input">Institución educativa</th> --}}
      {{-- <th data-field="status" data-filter-control="select" data-formatter="checkboxStatus" data-filter-data="var:filterDefaults">Estatus</th> --}}
      {{-- <th data-formatter="buttosAction">Acciones</th> --}}
      <th data-field="operate" data-click-to-select="false" data-events="operateEvents" data-formatter="operateFormatter">Editar</th>
      <th data-field="operate2" data-click-to-select="false" data-events="operateEvents2" data-formatter="operateFormatter2">Eliminar</th>
    </tr>
  </thead>
</table>
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre','required','autofocus']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('description','Descripción:') !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                    </div>
                </div>
          </div>
      </div>
        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveevent()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{asset('js/typeevent.js')}}"></script>
<script>
    var getURL = window.location;
    var baseURL = "{{url()->current()}}";
</script>
<script>
     var filterDefaults = {
            1: 'Activo',
            0: 'Inactivo'
        };
     $(function() {
        $('#table').bootstrapTable();
        $(".btn[name='clearSearch']").addClass('btn-secondary');
    });
    function buttons () {
    return {
      btnAdd: {
        text: 'Add new row',
        icon: 'fa-plus',
        event: function () {
          $("#addModal").modal('show');
        },
        attributes: {
          title: 'Agregar'
        }
      }
      // btnPrint: {
      //   text: 'Imprimir',
      //   icon: 'fa-file-excel-o',
      //   event: function () {
      //     alert('Do some stuff to e.g. add a new row')
      //   },
      //   attributes: {
      //     title: 'Imprimir'
      //   }
      // }
    }
    }
</script>
@endsection