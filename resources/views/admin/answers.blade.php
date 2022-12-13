@extends('layouts.app')

@section('content')

<div class="panel-heading">
    <h3 class="panel-title">Respuestas</h3>   
</div>

<button type="button" onclick="cleanAnswerForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="question" data-filter-control="input">Pregunta de Sobrevivencia</th>
      <th data-field="answer_a" data-filter-control="input">Respuesta (a)</th>
      <th data-field="answer_b" data-filter-control="input">Respuesta (b)</th>
      <th data-field="answer_c" data-filter-control="input">Respuesta (c)</th>
      <th data-field="answer_d" data-filter-control="input">Respuesta (d)</th>
      <th data-field="answer_e" data-filter-control="input">Respuesta (e)</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal respuestas --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Agregar Respuesta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <input id="id" type="hidden" value="">

          <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('survival_question_id','Pregunta:') !!}
                        {!! Form::select('survival_question_id',$question,null,['class'=>'form-control','placeholder'=>'Seleccione una opción','required','autofocus']) !!}
                    </div>
                </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('answer_a','Respuesta a:') !!}
                    {!! Form::text('answer_a',null,['class'=>'form-control','placeholder'=>'Respuesta','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('answer_b','Respuesta b:') !!}
                    {!! Form::text('answer_b',null,['class'=>'form-control','placeholder'=>'Respuesta','required']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('answer_c','Respuesta c:') !!}
                    {!! Form::text('answer_c',null,['class'=>'form-control','placeholder'=>'Respuesta','required']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('answer_d','Respuesta d:') !!}
                    {!! Form::text('answer_d',null,['class'=>'form-control','placeholder'=>'Respuesta']) !!}
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('answer_e','Respuesta e:') !!}
                    {!! Form::text('answer_e',null,['class'=>'form-control','placeholder'=>'Respuesta']) !!}
                </div>
            </div>
 
          </div>

      </div>
        {!! Form::close() !!}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="saveAnswer()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/answers.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection