@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Agenda de Adopciones</h3>   
</div>

<button type="button" onclick="cleanAdoptionForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="institution_name" data-filter-control="input">Institución</th>
      <th data-field="event_name" data-filter-control="input">Evento</th>
      <th data-field="adoption_date" data-filter-control="input">Fecha</th>
      <th data-field="adoption_time" data-filter-control="input">Hora</th>
      <th data-field="thecnical_user_name" data-filter-control="input">Responsable</th>
      <th data-field="code_event" data-filter-control="input">Código Evento</th>
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
        <h5 class="modal-title">Agendar Adopción</h5>
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
                        {!! Form::label('adoption_date','Fecha:') !!}
                        <input class="form-control" data-events="editTagTree" placeholder="Fecha" onchange="generateAdoptionCode()" name="adoption_date" type="date" id="adoption_date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('adoption_time','Hora:') !!}
                        {!! Form::time('adoption_time',null,['class'=>'form-control','placeholder'=>'Hora']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('educative_institution_lbl','Institución:') !!}
                        <select id="select_educative_institution_id" class="form-control" onchange="generateAdoptionCode()">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($educativeInstitutions as $educativeInstitution)
                            <option value="{{ $educativeInstitution->id }}">
                              {{ $educativeInstitution->institution_name }}
                            </option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('sponsor_lbl','Patrocinador:') !!}
                        <select id="select_sponsor_id" class="form-control" onchange="generateAdoptionCode()">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($sponsors as $sponsor)
                            <option value="{{ $sponsor->id }}">
                              {{ $sponsor->prefix_code_event }} - {{ $sponsor->enterprise_name }} 
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
                        <select id="select_event_id" class="form-control" onchange="generateAdoptionCode()">
                          <option hidden selected value="">Selecciona...</option>
                          @foreach($events as $event)
                            <option value="{{ $event->id }}">
                              {{ $event->prefix_code }} - {{ $event->name }} ({{ $event->type }})
                            </option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('technical_user_id','Responsable:') !!}
                        {!! Form::select('technical_user_id',$users,null,['class'=>'form-control','placeholder'=>'Selecciona...']) !!}
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name','Nombre:') !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('phone','Telefóno:') !!}
                        {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'Telefóno']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('email','Email:') !!}
                        {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Email']) !!}
                    </div>
                </div>
            </div> --}}
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('address','Dirección:') !!}
                        {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'Dirección']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('suburb','Colonia:') !!}
                        {!! Form::text('suburb',null,['class'=>'form-control','placeholder'=>'Colonia']) !!}
                    </div>
                </div>
            </div> --}}
            <div class="row">
                {{-- <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('postal_code','Código Postal:') !!}
                        {!! Form::text('postal_code',null,['class'=>'form-control','placeholder'=>'C.P.']) !!}
                    </div>
                </div> --}}
                {{-- <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('longitude','Longitud:') !!}
                        {!! Form::text('longitude',null,['class'=>'form-control','placeholder'=>'Longitud']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('latitude','Latitud:') !!}
                        {!! Form::text('latitude',null,['class'=>'form-control','placeholder'=>'Latitud']) !!}
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('code_event','Código del evento:') !!}
                        {!! Form::text('code_event',null,['class'=>'form-control','placeholder'=>'Código del evento', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <label for="species_lbl" class="input-group-text">Especies:</label>
                  </div>
                  <div class="form-control">
                    <select id="select_species_id" class="form-control">
                      <option hidden selected value="">Selecciona...</option>
                      @foreach($allSpecies as $species)
                        <option value="{{ $species->id }}">
                            {{ $species->name }}: {{ $species->scientific_name }} 
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group-append">
                    <button onclick="addSpeciesRow()" class="btn btn-outline-primary">Agregar</button>
                  </div>
                </div>
              </div>
            </div>
            <table id="species-table" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Nombre cientifico</th>
                  <th scope="col">Etiqueta</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Eliminar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <hr>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" onclick="printTags()">
            <span class="glyphicon glyphicon-print"></span> Imprimir
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="saveAdoption()" class="btn btn-primary">Guardar</button>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/adoptions.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";
  var educativeInstitutions = {!! json_encode($educativeInstitutions) !!};
  var allSpecies = {!! json_encode($allSpecies) !!};
  var events = {!! json_encode($events) !!};
  var sponsors = {!! json_encode($sponsors) !!};

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection
