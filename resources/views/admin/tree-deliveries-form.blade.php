@extends('layouts.app')

@section('content')
{{-- <div class="panel-heading">
    <h3 class="panel-title">Registrar Entrega de Árboles</h3>
</div> --}}

<div class="card">
    <div class="card-header">
        <div class="panel-heading">
            <h3 class="panel-title">Registrar Entrega de Árboles</h3>
        </div>
    </div>
    <div class="card-body">
        <input id="id" type="hidden" value="">
  
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input class="form-control" placeholder="Nombre" name="name" type="text" id="name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="phone">Telefóno:</label>
                    <input class="form-control" placeholder="Telefóno" name="phone" type="text" id="phone">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" placeholder="Email" name="email" type="text" id="email">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <label for="address">Dirección:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select id="road_type" class="form-control" style="padding-right: 30px">
                            <option selected value="Calle">Calle</option>
                            <option value="Privada">Privada</option>
                            <option value="Avenida">Avenida</option>
                        </select>
                    </div>
                    <input class="form-control" placeholder="Dirección" name="address" type="text" id="address">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="suburb">Colonia:</label>
                    <input class="form-control" placeholder="Colonia" name="suburb" type="text" id="suburb">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="postal_code">Código Postal:</label>
                    <input class="form-control" placeholder="C.P." name="postal_code" type="text" id="postal_code">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="adoption_species">Especie:</label>
                    <select id="adoption_species" class="form-control" style="padding-right: 30px">
                        <option hidden selected value="-1">Seleccione una especie</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                {!! Form::label('search_address_btn',' ') !!}
                <button id="search_address_btn" type="button" onclick="getGeocode()" class="btn btn-info form-control"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
            <div class="col-md-2">
                {!! Form::label('search_address_btn',' ') !!}
                <button type="button" class="btn btn-secondary form-control">
                    Cancelar
                </button>
            </div>
            <div class="col-md-2">
                {!! Form::label('search_address_btn',' ') !!}
                <button type="button" onclick="saveTreeDelevery()" class="btn btn-primary form-control">
                    Guardar
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('latitude','Latitud:') !!}
                    {!! Form::text('latitude',null,['class'=>'form-control','placeholder'=>'Latitud']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('longitude','Longitud:') !!}
                    {!! Form::text('longitude',null,['class'=>'form-control','placeholder'=>'Longitud']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('code_event','Código del evento:') !!}
                    {!! Form::text('code_event',null,['class'=>'form-control','placeholder'=>'Código del evento', 'disabled']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="mapid" style="height: 300px;"></div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="input-group">
                    {{-- <button type="button" onclick="readExcel()" class="btn btn-outline-primary">Cargar Excel</button> --}}
                    <div class="custom-file">
                        <input type="file" onchange="uploadExcel(event)"  onclick="this.value=null;" id="uploadExcelInp" accept=".xls,.xlsx"/>
                        <label class="custom-file-label button-xlsx" for="uploadExcelInp">Elegir archivo</label>
                    </div>
                </div>
            </div>
                <button id="delete_excel_table" type="button" onclick="deleteExcel()" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </div>
        
    </div>
</div>

<div hidden id="load-xls">
    <hr>
    <h3>Tabla cargada desde excel</h3>
<table 
  class="table table-striped mt-3"
  id="table-2"
  data-id-field="id"
  data-pagination="true"
  data-page-list="[10, 25, 50, 100, 200]"
  data-page-size="25"
  data-toggle = "table"
  data-filter-control="true"
  data-show-toggle="false"
  data-buttons-class="primary"
  data-show-search-clear-button="false"
  data-buttons-align="center"
  data-show-footer="true">
  <thead>
    <tr>
      {{--<th data-field="id" data-filter-control="input">No.</th> --}}
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="phone" data-filter-control="input">Teléfono</th>
      <th data-field="mail" data-filter-control="input">Email</th>
      {{--<th data-field="address" data-filter-control="input">Dirección</th>--}}
      {{--<th data-field="suburb" data-filter-control="input">Municipio</th>--}}
      <th data-field="species_name" data-filter-control="input">Especie</th>
      {{--<th data-field="Latitud" data-filter-control="input">Latitud</th>
      <th data-field="Longitud" data-filter-control="input">Longitud</th>--}}
      <th data-field="edit" data-click-to-select="false" data-events="editRegisterExcelEvent" data-formatter="editFormFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteRegisterExcelEvent" data-formatter="deleteColumnFormatter">Eliminar</th>

    </tr>
  </thead>
</table>
<button type="button" onclick="saveExcel()" class="btn btn-primary">Guardar excel</button>
</div>
<br/>

<hr>


<h3>Base de datos</h3>
<table
  class="table table-striped mt-3"
  id="table-3"
  data-id-field="id"
  data-pagination="true"
  data-page-list="[10, 25, 50, 100, 200]"
  data-page-size="25"
  data-toggle = "table"
  data-filter-control="true"
  data-show-toggle="false"
  data-buttons-class="primary"
  data-show-search-clear-button="false"
  data-buttons-align="center"
  data-show-footer="true">
  <thead>
    <tr>
      {{--<th data-field="id" data-filter-control="input">No.</th> --}}
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="phone" data-filter-control="input">Teléfono</th>
      <th data-field="mail" data-filter-control="input">Email</th>
      {{--<th data-field="address" data-filter-control="input">Dirección</th>--}}
      {{--<th data-field="suburb" data-filter-control="input">Municipio</th>--}}
      <th data-field="species_name" data-filter-control="input">Especie</th>
      {{--<th data-field="Latitud" data-filter-control="input">Latitud</th>
      <th data-field="Longitud" data-filter-control="input">Longitud</th>--}}
      <th data-field="edit" data-click-to-select="false" data-events="editRegisterEvent" data-formatter="editFormFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteRegisterEvent" data-formatter="deleteColumnFormatter">Eliminar</th>

    </tr>
  </thead>
</table>
@endsection

@section('js')
<script src="{{asset('js/tree-deliveries.js')}}"></script>
<script>
    var getURL = window.location;
    var baseURL = "{{ url()->current() }}";
    var myMap = L.map('mapid');
    var myMapPopup = L.popup();
    var myMapCircleMarker = L.circleMarker([], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 5
    });
    var selectedFile = null;

    $(function() {
        // initializeTable();
        loadTreeDeliveriesForm();
        loadAllSpecies();
        settingUpMap();
        $(".btn[name='clearSearch']").addClass('btn-secondary');
    });
</script>
@endsection
