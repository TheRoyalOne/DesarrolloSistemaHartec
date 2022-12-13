@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Especies</h3>   
</div>

<button type="button" onclick="cleanSpeciesForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="name" data-filter-control="input">Nombre</th>
      <th data-field="scientific_name" data-filter-control="input">Nombre Cientifico</th>
      <th data-field="recovery_fee_a" data-filter-control="input">Cuota Recuperacion A</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal species --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Especies</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="" enctype="multipart/form-data" onsubmit="guardarspecies(this);return false;"> --}}
      <form id="form" action="" enctype="multipart/form-data">
        <div class="modal-body">
            <input id="id" type="hidden" value="">

            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      {!! Form::label('name','Nombre de la Especie:') !!}
                      {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre','autofocus']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('scientific_name','Nombre Cientifico:') !!}
                      {!! Form::text('scientific_name',null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('recovery_fee_a','Donativo a') !!}
                      {!! Form::text('recovery_fee_a',null,['class'=>'form-control','placeholder'=>'altura=x']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('recovery_fee_b','Donativo b:') !!}
                      {!! Form::text('recovery_fee_b',null,['class'=>'form-control','placeholder'=>'altura>a']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('recovery_fee_c','Donativo c') !!}
                      {!! Form::text('recovery_fee_c',null,['class'=>'form-control','placeholder'=>'altura>b']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_1','Especificación 1:') !!}
                      {!! Form::select('spec_1',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_2','Especificación 2:') !!}
                      {!! Form::select('spec_2',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_3','Especificación 3:') !!}
                      {!! Form::select('spec_3',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_4','Especificación 4:') !!}
                      {!! Form::select('spec_4',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
            </div>          
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_5','Especificacion 5:') !!}
                      {!! Form::select('spec_5',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}

                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('spec_6','Especificación 6:') !!}
                      {!! Form::select('spec_6',$specs,null,['class'=>'form-control','placeholder'=>'Especificación']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('observations','Observaciones:') !!}
                      {!! Form::text('observations',null,['class'=>'form-control','placeholder'=>'Observacion']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('picture','Fotografia:') !!}
                      {!! Form::file('picture',null,['class'=>'form-control']) !!}
                  </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/species.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection