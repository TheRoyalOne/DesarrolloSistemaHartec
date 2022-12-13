@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Patrocinadores</h3>
</div>

<button type="button" onclick="cleanSponsorForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="enterprise_name" data-filter-control="input">Nombre de la compáñia</th>
      {{-- <th data-field="institutional_charge" data-filter-control="input"></th> --}}
      <th data-field="contact-info" data-click-to-select="false" data-filter-control="input" data-formatter="contactInfoColumnFormatter">Contacto</th>
      <th data-field="cellphone" data-filter-control="input">Teléfono de contacto</th>
      <th data-field="email" data-filter-control="input">Correo de contacto</th>
      {{-- <th data-field="end_sponsorship" data-filter-control="input">Fin de patrocinio</th> --}}
      <th data-field="user" data-filter-control="input" data-formatter="userName">Responsable</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal patrocinadores --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Patrocinador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="" enctype="multipart/form-data" onsubmit="savesponsor(this); return false;"> --}}
      <form id="form" action="" enctype="multipart/form-data">
        <div class="modal-body">
          <input id="id" type="hidden" value="">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('enterprise_name','Nombre de la compáñia:') !!}
                {!! Form::text('enterprise_name',null,['class'=>'form-control','placeholder'=>'Nombre']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('social_reason','Razón social:') !!}
                {!! Form::text('social_reason',null,['class'=>'form-control','placeholder'=>'Razón']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('rfc','RFC:') !!}
                {!! Form::text('rfc',null,['class'=>'form-control','placeholder'=>'RFC']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('prefix_code_event','Prefijo para código:') !!}
                {!! Form::text('prefix_code_event',null,['class'=>'form-control','placeholder'=>'Prefijo']) !!}
                {{-- {!! Form::text('prefix_code_event',null,['class'=>'form-control','placeholder'=>'Prefijo']) !!} --}}
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('size','Tamaño:') !!}
                <select class="form-control" name="size" id="size" >
                  <option value="1" selected>Micro</option>
                  <option value="2" >Chica</option>
                  <option value="3" >Mediana</option>
                  <option value="4" >Grande</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('roll','Giro:') !!}
                <select class="form-control" name="roll" id="roll" >
                  <option value="1" selected>Industrial</option>
                  <option value="2" >Comercial</option>
                  <option value="3" >servicio</option>
                  <option value="4" >Gobierno</option>
                </select>
              </div>
            </div>
          </div>



          <hr style="border-bottom: 2px solid rgb(0, 255, 21)">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <p>Contacto de la empresa</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('name','Nombre:') !!}
                {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Nombre']) !!}
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('firstname','Apellido Paterno:') !!}
                {!! Form::text('firstname',null,['class'=>'form-control','placeholder'=>'Apellido Paterno']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('lastname','Apellido Materno:') !!}
                {!! Form::text('lastname',null,['class'=>'form-control','placeholder'=>'Apellido Materno']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('email','Correo:') !!}
                {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Correo']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('cellphone','Celular:') !!}
                {!! Form::text('cellphone',null,['class'=>'form-control','placeholder'=>'Celular']) !!}
              </div>
            </div>
          </div>
          <hr style="border-bottom: 2px solid rgb(0, 255, 21)">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('address','Direccion:') !!}
                {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'Direccion']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('numE','num Ext:') !!}
                {!! Form::text('numE',null,['class'=>'form-control','placeholder'=>'#']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('numI','num Int:') !!}
                {!! Form::text('numI',null,['class'=>'form-control','placeholder'=>'#']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('colony','Colonia:') !!}
                {!! Form::text('colony',null,['class'=>'form-control','placeholder'=>'colonia']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('postal_code','Código postal:') !!}
                {!! Form::text('postal_code',null,['class'=>'form-control','placeholder'=>'CP']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('observations','Observaciones:') !!}
                {!! Form::text('observations',null,['class'=>'form-control','placeholder'=>'Observaciones']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('website','Sitio Web:') !!}
                {!! Form::text('website',null,['class'=>'form-control','placeholder'=>'link']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('logotype','Logo Tipo:') !!}
                {!! Form::file('logotype',null,['class'=>'form-control','placeholder'=>'Logo tipo']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('id_event_adoptions','Evento de Adopción:') !!}
                {!! Form::text('id_event_adoptions',null,['class'=>'form-control','placeholder'=>'Adopción']) !!}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('id_event_workshops','Evento de Talleres:') !!}
                {!! Form::text('id_event_workshops',null,['class'=>'form-control','placeholder'=>'Taller']) !!}
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="end_sponsorship">Fecha final de patrocinio:</label>
                <input class="form-control" placeholder="Hora" name="end_sponsorship" type="date" id="end_sponsorship">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('user','Responsable:') !!}
                <select id="user" name="user" class="form-control">
                  <option hidden selected value="">Selecciona...</option>
                  @foreach($users as $user)
                      <option value="{{ $user->id }}">
                          {{ $user->name }}
                      </option>
                  @endforeach
              </select>
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
<script src="{{asset('js/sponsors.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection