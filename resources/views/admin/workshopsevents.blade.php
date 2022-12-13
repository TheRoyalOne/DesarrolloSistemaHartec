@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Eventos</h3>   
</div>

<button type="button" onclick="cleanWorkshopForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModalWorkshop" data-whatever="@mdo">Taller</button>
<button type="button" onclick="cleanAdoptionForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModaladoption" data-whatever="@mdo">Adopción</button>
<button type="button" onclick="cleanReforestationForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModalreforest" data-whatever="@mdo">Reforestación</button>
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
      <th data-field="id" data-filter-control="input">No.</th>
      <th data-field="type" data-filter-control="input">Tipo</th>
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="prefix_code" data-filter-control="input">Prefijo para código</th>
      <th data-field="description" data-filter-control="input">Descripción</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal talleres --}}
<div class="modal" tabindex="-1" role="dialog" id="addModalWorkshop">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Taller</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <input id="event_id" type="hidden" value="">

          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name_workshop','Nombre del Evento:') !!}
                        {!! Form::text('name_workshop',null,['class'=>'form-control','placeholder'=>'Nombre','required','autofocus']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('prefix_code_workshop','Prefijo para código:') !!}
                        {!! Form::text('prefix_code_workshop',null,['class'=>'form-control','placeholder'=>'Prefijo','required', 'maxlength'=>'3']) !!}
                    </div>
                </div>
          </div>

          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('rec_fee_online','Cuota online:') !!}
                    {!! Form::text('rec_fee_online',null,['class'=>'form-control','placeholder'=>'Cuota','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('rec_fee_presencial','Cuota Presencial:') !!}
                    {!! Form::text('rec_fee_presencial',null,['class'=>'form-control','placeholder'=>'Cuota','required']) !!}
                </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('rec_fee_business','Cuota Empresarial:') !!}
                    {!! Form::text('rec_fee_business',null,['class'=>'form-control','placeholder'=>'Cuota','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('rec_fee_online_kits','Cuota Onlie + Kits:') !!}
                    {!! Form::text('rec_fee_online_kits',null,['class'=>'form-control','placeholder'=>'Cuota','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('description_workshop','Descripción:') !!}
                    {!! Form::textarea('description_workshop',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                </div>
            </div>
          </div>

      </div>
        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" onclick="cleanWorkshopForm()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveWorkshop()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

{{-- modal adopciones --}}
<div class="modal" tabindex="-1" role="dialog" id="addModaladoption">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Adopcion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name_adoption','Nombre de Evento:') !!}
                        {!! Form::text('name_adoption',null,['class'=>'form-control','placeholder'=>'Evento','required','autofocus']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('prefix_code_adoption','Prefijo para código:') !!}
                        {!! Form::text('prefix_code_adoption',null,['class'=>'form-control','placeholder'=>'Prefijo','required', 'maxlength'=>'3']) !!}
                    </div>
                </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('donation_adoption','Donativo por arbol:') !!}
                    {!! Form::text('donation_adoption',null,['class'=>'form-control','placeholder'=>'Donativo','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('description_adoption','Descripción:') !!}
                    {!! Form::textarea('description_adoption',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                </div>
            </div>
           </div>
          
      </div>

        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" onclick="cleanAdoptionForm()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveAdoption()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

{{-- modal reforestaciones --}}
<div class="modal" tabindex="-1" role="dialog" id="addModalreforest">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Reforestacion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name_reforest','Nombre de Evento:') !!}
                        {!! Form::text('name_reforest',null,['class'=>'form-control','placeholder'=>'Evento','required','autofocus']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('prefix_code_reforest','Prefijo para código:') !!}
                        {!! Form::text('prefix_code_reforest',null,['class'=>'form-control','placeholder'=>'Prefijo','required', 'maxlength'=>'3']) !!}
                    </div>
                </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('donation_reforest','Donativo por árbol:') !!}
                    {!! Form::text('donation_reforest',null,['class'=>'form-control','placeholder'=>'Donativo','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('description_reforest','Descripción:') !!}
                    {!! Form::textarea('description_reforest',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                </div>
            </div>
           </div>
          
      </div>

        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" onclick="cleanReforestationForm()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="savereReforestation()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/workshopsevents.js')}}"></script>
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