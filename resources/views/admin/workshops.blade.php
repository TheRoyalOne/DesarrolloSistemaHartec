@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Agenda de Talleres</h3>
</div>

<button type="button" onclick="cleanWorkshopForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="sponsor_name" data-filter-control="input">Patrocinador</th>
      <th data-field="educative_institution_name" data-filter-control="input">Institución</th>
      <th data-field="event_name" data-filter-control="input">Evento</th>
      <th data-field="rec_fee_type" data-filter-control="input">Tipode Cuota</th>
      <th data-field="rec_fee" data-filter-control="input">Cuota de Recuperación</th>
      <th data-field="workshop_date" data-filter-control="input">Fecha</th>
      <th data-field="workshop_time" data-filter-control="input">Hora</th>
      <th data-field="workshop_user_name" data-filter-control="input">Tallerista</th>
      <th data-field="code_event" data-filter-control="input">Código</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal species --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agendar Taller</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="" enctype="multipart/form-data" onsubmit="guardarspecies(this);return false;"> --}}
      {{-- <form id="form" action="" enctype="multipart/form-data"> --}}
        <div class="modal-body">
            <input id="id" type="hidden" value="">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('sponsor_lbl','Patrocinador:') !!}
                        <select id="select_sponsor_id" class="form-control" onchange="generateWorkshopCode()">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($sponsors as $sponsor)
                            <option value="{{ $sponsor->id }}">
                              {{ $sponsor->prefix_code_event }} - {{ $sponsor->enterprise_name }} 
                            </option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('educative_institution_lbl','Institución:') !!}
                        <select id="select_educative_institution_id" class="form-control" onchange="generateWorkshopCode()">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($educativeInstitutions as $educativeInstitution)
                            <option value="{{ $educativeInstitution->id }}">
                              {{ $educativeInstitution->institution_name }}
                            </option>
                          @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('event_lbl','Evento:') !!}
                        <select id="select_event_id" class="form-control" onchange="generateWorkshopCode(); setRecFee(); return;">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($events as $event)
                            <option value="{{ $event->id }}">
                              {{ $event->prefix_code }} - {{ $event->name }} ({{ $event->type }})
                            </option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                      {!! Form::label('select_rec_fee_type','Tipo de cuota:') !!}
                      {!! Form::select('select_rec_fee_type',$recFeeTypes,null,['class'=>'form-control','placeholder'=>'Selecciona...', 'onchange'=>'setRecFee()',]) !!}
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('rec_fee','Cuota de recuperación:') !!}
                        {!! Form::text('rec_fee',null,['class'=>'form-control','placeholder'=>'-', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('workshop_date','Fecha:') !!}
                        {!! Form::date('workshop_date',null,['class'=>'form-control','placeholder'=>'Fecha','autofocus', 'onchange'=>'generateWorkshopCode()']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('workshop_time','Hora:') !!}
                        {!! Form::time('workshop_time',null,['class'=>'form-control','placeholder'=>'Hora']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('select_workshop_user_id','Tallerista:') !!}
                        {!! Form::select('select_workshop_user_id',$users,null,['class'=>'form-control','placeholder'=>'Selecciona...']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('code_event','Código:') !!}
                      {!! Form::text('code_event',null,['class'=>'form-control','placeholder'=>'###-###-######', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    {!! Form::label('workshop_material_lbl','Materiales:', ['class'=>'input-group-text']) !!}
                  </div>
                  <div class="form-control">
                    <select id="select_workshop_material_id" class="form-control">
                      <option hidden selected value="">Selecciona...</option>
                      @foreach($workshopMaterials as $workshopMaterial)
                        <option value="{{ $workshopMaterial->id }}">
                          {{ $workshopMaterial->name }}: {{ $workshopMaterial->description ?? "-"}} 
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group-append">
                    <button onclick="addMaterialRow()" class="btn btn-outline-primary" type="button">Agregar</button>
                  </div>
                </div>
              </div>
            </div>
            <table id="materials-table" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Eliminar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <hr>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="saveWorkshop()" class="btn btn-primary">Guardar</button>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/workshops.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";
  var educativeInstitutions = {!! json_encode($educativeInstitutions) !!};
  var workshopMaterials = {!! json_encode($workshopMaterials) !!};
  var events = {!! json_encode($events) !!};
  var sponsors = {!! json_encode($sponsors) !!};

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection
