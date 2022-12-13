@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Salida de Materiales</h3>   
</div>

<button type="button" onclick="cleanMaterialLeavingForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="workshop_name" data-filter-control="input">Taller</th>
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
        <h5 class="modal-title">Registrar Salida de Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input id="id" type="hidden" value="">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('workshop_id','Evento:') !!}
                    {!! Form::select('workshop_id',$events,null,['class'=>'form-control','placeholder'=>'Selecciona...']) !!}
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  {!! Form::label('technical_user_id','Técnico:') !!}
                  {!! Form::select('technical_user_id',$users,null,['class'=>'form-control','placeholder'=>'Selecciona...']) !!}
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  {!! Form::label('leaving_date','Fecha:') !!}
                  {!! Form::date('leaving_date',null,['class'=>'form-control','placeholder'=>'Fecha']) !!}
              </div>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-md-12">
            <div class="input-group form-group">
              <div class="input-group-prepend">
                {!! Form::label('materials_lbl','Materiales:', ['class'=>'input-group-text']) !!}
              </div>
              <div class="form-control">
                {!! Form::select('select_materials_id',$workshopMaterials,null,['class'=>'form-control', 'id'=>'select_materials_id','placeholder'=>'Selecciona...']) !!}
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
              <th scope="col">Cantidad (a retirar/solicitada)</th>
              <th scope="col">Eliminar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <hr> 


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveMaterialLeaving()" class="btn btn-primary">Guardar</button>
      </div>
      {{-- </form> --}}
    </div>
  </div>
</div>
@endsection





@section('js')
<script src="{{asset('js/material-leavings.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";
  var workshopMaterials = {!! json_encode($workshopMaterials) !!};

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection