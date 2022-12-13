@extends('layouts.app')
@section('content')

<div class="panel-heading">
    <h3 class="panel-title">Usuarios</h3>   
</div>

<button type="button" onclick="cleanUserForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
<table
  class="table table-striped mt-3"
  id="table"
  data-id-field="id"
  data-pagination="true"
  data-page-list="[10, 25, 50, 100, 200]"
  data-page-size="25"
  data-filter-control="true"
  data-show-toggle="false"
  data-buttons-class="primary"
  data-show-search-clear-button="false"
  data-buttons-align="center"
  data-show-footer="true">
  <thead>
    <tr>
      <th data-field="id" data-filter-control="input">#</th>
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="lastname" data-filter-control="input">Apellido Paterno</th>
      <th data-field="lastname2" data-filter-control="input">Apellido Materno</th>
      <th data-field="email" data-filter-control="input">Correo electrónico</th>
      {{-- <th data-field="username" data-filter-control="input">Usuario</th> --}}
      {{-- <th data-field="lastname2" data-filter-control="input">Contraseña</th> --}}
      <th data-field="job" data-filter-control="input">Puesto</th>
      <th data-field="profile" data-filter-control="input">Perfil</th>
      {{-- <th data-field="educative_institution_id" data-filter-control="input">Institución educativa</th> --}}
      {{-- <th data-field="status" data-filter-control="select" data-formatter="checkboxStatus" data-filter-data="var:filterDefaults">Estatus</th> --}}
      {{-- <th data-formatter="buttosAction">Acciones</th> --}}
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal usuarios --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <input id="id" type="hidden" value="">

          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('username','Nombre de Usuario:') !!}
                        {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'Usuario','required','autofocus']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('password','Contraseña:') !!}
                        {!! Form::text('password',null,['class'=>'form-control','placeholder'=>'Contraseña','required']) !!}
                    </div>
                </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('name','Nombre:') !!}
                    {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('lastname','Apellido Paterno:') !!}
                    {!! Form::text('lastname',null,['class'=>'form-control','placeholder'=>'Apellido Paterno','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('lastname2','Apellido Materno:') !!}
                    {!! Form::text('lastname2',null,['class'=>'form-control','placeholder'=>'Apellido Materno','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('cellphone','Celular:') !!}
                    {!! Form::text('cellphone',null,['class'=>'form-control','placeholder'=>'Celular','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('job','Puesto:') !!}
                    {!! Form::text('job',null,['class'=>'form-control','placeholder'=>'Puesto','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  {!! Form::label('educative_institution_id','Institución Educacional:') !!}
                  {!! Form::select('educative_institution_id',$educative_institutions,null,['class'=>'form-control','placeholder'=>'Seleccione una opción']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('email','Correo:') !!}
                    {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Correo','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('profile_id','Perfil:') !!}
                    {!! Form::select('profile_id',$profiles,null,['class'=>'form-control','placeholder'=>'Seleccione una opcion']) !!}
                </div>
            </div>
          </div>
      </div>
        {{-- <div class="form-group">
            {!! Form::label('name','Nombre:') !!}
            {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre','required','autofocus']) !!}
        </div> --}}
        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveUser()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
  <script src="{{asset('js/users.js')}}"></script>
  <script>
    var getURL = window.location;
    var baseURL = "{{url()->current()}}";
      
    var filterDefaults = {
      1: 'Activo',
      0: 'Inactivo'
    };

    // Inicilizando la pagina
    $(function() {
      initializeTable();
      $(".btn[name='clearSearch']").addClass('btn-secondary');
    });
  </script>
@endsection