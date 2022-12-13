@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Cuestionario</h3>
</div>


<div class="d-flex justify-content-between">
  <button type="button" onclick="cleanForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">
    Aplicar encuesta
  </button>

  <button type="button" onclick="lastQuestion(this)" class="btn btn-success" data-toggle="modal" data-whatever="@mdo">
    regresar a la ultima encuesta
  </button>
</div>

{{-- CONTADOR DE ENCUESTAS TOTALES --}}
<br/> <br/>
<div class="">
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('Sponsor_id','Patrocinador:') !!}
        <select onchange="getTotales(this)" id="Sponsor_report" class="form-control">
          <option selected value="a">Todos</option>
          @foreach($Sponsor as $s)
            <option value="{{ $s->id }}">
                {{ $s->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('Sponsor_id','Encuesta:') !!}
        <select onchange="getTotales(this)" id="Questionnaire_report" class="form-control">
          @foreach($questions_init as $question)
            <option value="{{ $question->id }}">
                {{ $question->sentence }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-sm-6" style="display: none;">
      <div class="form-group">
        {!! Form::label('total','Encuestas totales:') !!}
        <input type="text" id="total" class="form-control" value="#" disabled>
      </div>
    </div>
  </div>

<div class="">
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <label for="f_inicio">Fecha inicio:</label>
        <input class="form-control" placeholder="Hora" name="f_inicio" type="date" id="f_inicio" onchange="getTotales(this)">
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        <label for="f_fin">Fecha fin:</label>
        <input class="form-control" placeholder="Hora" name="f_fin" type="date" id="f_fin" onchange="getTotales(this)">
      </div>
    </div>
  </div>
</div>
<br/>

<div class="questionnaire-graphic" id="report">
  {{-- RELLENAR MEDIANTE JS --}}

</div>

<br />
<button onclick="printPdf()" type="button" class="btn btn-primary">Imprimir reporte</button>

{{-- --------------------------------------------------------- --}}

{{-- modal principal --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Aplicar encuesta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input id="id" type="hidden" value="">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Sponsor_id','Patrocinador:') !!}
                    <select onchange="primeModal(this)" id="Sponsor_id" class="form-control">
                      <option hidden selected value="-1">Selecciona...</option>
                      @foreach($Sponsor as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->name }}
                        </option>
                      @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  {!! Form::label('question_idx','Elija el cuestionario:') !!}
                  <select onchange="primeModal(this)" id="question_idx" class="form-control">
                    <option hidden selected value="-1">Selecciona...</option>
                    @foreach($questions_init as $question)
                      <option value="{{ $question->id }}">
                          {{ $question->sentence }}
                      </option>
                    @endforeach
                  </select>
              </div>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button
          type="button"
          onclick="getRandomUser()"
          class="btn btn-primary"
          id="modal_prime"
          data-dismiss="modal"
          data-target="#vista_usuario"
          data-toggle="modal"
          data-whatever="@mdo"
          disabled>
            Siguiente
        </button>
      </div>
      {{-- </form> --}}
    </div>
  </div>
</div>


{{-- modal vista usuario --}}
<div class="modal" tabindex="-1" role="dialog" id="vista_usuario">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Datos del usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Patrocinador:</span>
                </div>
                <input type="text" class="form-control" id="sponsor_name" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="">Evento:</span>
                </div>
                <input type="text" id="event" class="form-control" value="nombre del evento" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="">Nombre:</span>
                </div>
                <input type="text" id="buyer" class="form-control" value="Nombre de la persona" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="">Telefono:</span>
                </div>
                <input type="text" id="phone" class="form-control" value="33-3333-3333" disabled>
              </div>
            </div>
          </div>
        </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-target="#addModal" data-toggle="modal" data-dismiss="modal">Atras</button>
        <button
          type="button"
          onclick="initQuestion()"
          class="btn btn-primary"
          id="modal_questions"
          data-dismiss="modal"
          data-toggle="modal"
          disabled
          data-whatever="@mdo">
            Comenzar encuesta
        </button>
      </div>
      {{-- </form> --}}
    </div>
  </div>
</div>


{{-- modal Cuestionario --}}
@foreach($questions as $question)

<div class="modal" tabindex="-1" role="dialog" id="questionnarie-{{$question->id}}">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cuestionario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="row">
            <div class="col-md-12">
                <label for="html">Pregunta:</label>
                <br />
                <label for="html" class="title-question mb-0">{{$question->sentence }}</label>
            </div>
        </div>

        <hr>

        <div class="row mt-5">
          <div class="col-md-6">
              <div class="form-group">
                @foreach($question->answers as $answer)
                  <input type="radio" onchange="nextQuestion(this)" class="btn-check input-answer" name="options-{{$question->id}}" data-next={{$answer->id_next_question}} data-answer={{$answer->id}} id="question-{{$answer->id}}">
                  <label class="btn btn-outline-success" for="question-{{$answer->id}}">{{$answer->text}}</label>
                @endforeach
              </div>
          </div>
        </div>



      </div>
      <div class="modal-footer">
        <button type="button" onclick="popQuestion(this)" id="return-{{$question->id}}" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-whatever="@mdo">Atras</button>
        <button type="button" onclick="stackQuestion(this)" class="btn btn-primary btn-next" id="modal-{{$question->id}}" data-dismiss="modal" data-toggle="modal" data-whatever="@mdo" disabled>Siguiente</button>
      </div>
      {{-- </form> --}}
    </div>
  </div>
</div>

@endforeach
@endsection


@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


<script src="{{asset('js/questionnaire.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection