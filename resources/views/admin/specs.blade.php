@extends('layouts.app')
@section('content')

<div class="panel-heading">
    <h3 class="panel-title">Especificaciones</h3>   
</div>

<button type="button" onclick="cleanSpecForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      {{-- <th data-field="spec_1" data-filter-control="input">Especificación 1</th> --}}
      {{-- <th data-field="spec_2" data-filter-control="input">Especificación 2</th> --}}
      {{-- <th data-field="spec_3" data-filter-control="input">Correo electrónico</th> --}}
      {{-- <th data-field="username" data-filter-control="input">Usuario</th> --}}
      {{-- <th data-field="lastname2" data-filter-control="input">Contraseña</th> --}}
      {{-- <th data-field="spec_4" data-filter-control="input">Puesto</th> --}}
      {{-- <th data-field="spec_5" data-filter-control="input">Perfil</th> --}}
      {{-- <th data-field="educational_institution_id" data-filter-control="input">Institución educativa</th> --}}
      {{-- <th data-field="status" data-filter-control="select" data-formatter="checkboxStatus" data-filter-data="var:filterDefaults">Estatus</th> --}}
      {{-- <th data-formatter="buttosAction">Acciones</th> --}}
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal specs --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Especificación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input id="id" type="hidden" value="">

        <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      {!! Form::label('name','Nombre de la Especificación:') !!}
                      {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre','required','autofocus']) !!}
                  </div>
              </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_frut1','Frutales:') !!}
                  {!! Form::text('spec_frut1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_frut2','') !!}
                  {!! Form::text('spec_frut2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_frut3','') !!}
                {!! Form::text('spec_frut3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_orn1','Coniferas y Maderables:') !!}
                  {!! Form::text('spec_orn1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_orn2','C y M 2') !!}
                  {!! Form::text('spec_orn2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_orn3','Cy M 3') !!}
                {!! Form::text('spec_orn3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_conymad1','Caducifoliar:') !!}
                  {!! Form::text('spec_conymad1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_conymad2','Cad 2') !!}
                {!! Form::text('spec_conymad2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_conymad3','Cad 3') !!}
                {!! Form::text('spec_conymad3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_hojacad1','Perene:') !!}
                  {!! Form::text('spec_hojacad1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_hojacad2','') !!}
                {!! Form::text('spec_hojacad2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_hojacad3','') !!}
                {!! Form::text('spec_hojacad3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_banq1','Banquetas:') !!}
                  {!! Form::text('spec_banq1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_banq2','') !!}
                {!! Form::text('spec_banq2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_banq3','') !!}
                {!! Form::text('spec_banq3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_llan1','Llanos:') !!}
                  {!! Form::text('spec_llan1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_llan2','') !!}
                {!! Form::text('spec_llan2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_llan3','') !!}
                {!! Form::text('spec_llan3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_mac1','Maceta:') !!}
                  {!! Form::text('spec_mac1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_mac2','') !!}
                {!! Form::text('spec_mac2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_mac3','') !!}
                {!! Form::text('spec_mac3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_azotea1','Azoteas:') !!}
                  {!! Form::text('spec_azotea1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_azotea2','') !!}
                {!! Form::text('spec_azotea2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_azotea3','') !!}
                {!! Form::text('spec_azotea3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_int1','Interiores:') !!}
                  {!! Form::text('spec_int1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_int2','') !!}
                {!! Form::text('spec_int2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_int3','') !!}
                {!! Form::text('spec_int3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_ext1','Exteriores:') !!}
                  {!! Form::text('spec_ext1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_ext2','') !!}
                {!! Form::text('spec_ext2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_ext3','') !!}
                {!! Form::text('spec_ext3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_plant1','Plantas Medicinales:') !!}
                  {!! Form::text('spec_plant1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_plant2','') !!}
                {!! Form::text('spec_plant2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_plant3','') !!}
                {!! Form::text('spec_plant3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  {!! Form::label('spec_suc1','Cacteas y Suculentas:') !!}
                  {!! Form::text('spec_suc1',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_suc2','') !!}
                {!! Form::text('spec_suc2',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('spec_suc3','') !!}
                {!! Form::text('spec_suc3',null,['class'=>'form-control','placeholder'=>'Especificación','required']) !!}
            </div>
          </div>
        </div>
      </div>
        {!! Form::close() !!}
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveSpec()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/specs.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{url()->current()}}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection