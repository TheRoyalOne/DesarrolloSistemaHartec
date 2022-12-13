@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Levantamiento de reforestacion</h3>   
</div>

<button type="button" onclick="cleanTreeLeavingForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>

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
      {{-- <th data-field="id" data-filter-control="input">No.</th> --}}
      <th data-field="adoption_code_event" data-filter-control="input">Evento de Adopción</th>
      {{-- <th data-field="nursery_name" data-filter-control="input">Vivero</th> --}}
      {{-- <th data-field="species_name" data-filter-control="input">Especies</th>
      <th data-field="amount" data-filter-control="input">Cantidad (numero)</th> --}}
      <th data-field="labels" data-filter-control="input">Etiquetas</th>
      <th data-field="technical_user_name" data-filter-control="input">Responsable/Técnico</th>
      <th data-field="leaving_date" data-filter-control="input">Fecha de salida</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal Instituciones Educativas --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Levantamiento de Reforestación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="" enctype="multipart/form-data" onsubmit="saveinstitution(this); return false;"> --}}
      {{-- <form id="form" action="" enctype="multipart/form-data"> --}}
        <div class="modal-body">
            <input id="id" type="hidden" value="">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('reforestation_lbl','Evento de Reforestación:') !!}
                        <select id="select_adoption_id" class="form-control" onchange="fetchAdoptionSpecies()">
                            <option hidden selected value="">Selecciona...</option>
                            @foreach($reforestations as $reforestation)
                                <option value="{{ $reforestation->id }}">
                                    {{ $reforestation->code_event }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('technical_user_id','Técnico:') !!}
                        {!! Form::select('technical_user_id',$users,null,['class'=>'form-control','placeholder'=>'Selecciona...']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      {!! Form::label('check_date','Fecha:') !!}
                      {!! Form::date('check_date',null,['class'=>'form-control','placeholder'=>'Fecha']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{-- Aqui iban etiquetas --}}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    
                  </div>
                  <div class="form-control">
                    <select id="select_species_id" class="form-control">
                      <option hidden selected value="">Selecciona...</option>
                      
                    </select>
                  </div>
                  <div class="input-group-append">
                    <button onclick="addSpeciesRow()" class="btn btn-outline-primary" type="button">Agregar</button>
                  </div>
                </div>
              </div>
            </div>
            <table id="species-table" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Nombre cientifico</th>
                  <th scope="col">Cantidad prometida</th>
                  <th scope="col">Cantidad de muertos</th>
                  <th scope="col">Cantidad de enfermos</th>
                  <th scope="col">Eliminar</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <hr>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="saveTreeCheck()" class="btn btn-primary">Guardar</button>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>

@endsection



@section('js')

<script src="{{asset('js/reforestationSurvey.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{url()->current()}}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });

</script>
@endsection