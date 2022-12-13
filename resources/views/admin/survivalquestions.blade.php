@extends('layouts.app')
@section('content')

<div class="panel-heading">
    <h3 class="panel-title">Preguntas</h3>   
</div>

<button type="button" onclick="cleanSurvivalQuestionForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="question" data-filter-control="input">Pregunta</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal preguntas de sobrevivencia --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Pregunta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <input id="id" type="hidden" value="">

          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      {!! Form::label('question','Pregunta:') !!}
                      {!! Form::text('question',null,['class'=>'form-control','placeholder'=>'Pregunta','required','autofocus']) !!}
                  </div>
              </div>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveSurvivalQuestion()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/survivalquestions.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection