@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Institución Educativa</h3>   
</div>

<button type="button" onclick="cleanEducativeInstitutionForm()" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@mdo">Agregar</button>
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
      <th data-field="institution_name" data-filter-control="input">Nombre de la Institución</th>
      <th data-field="observations" data-filter-control="input">Observaciones</th>
      <th data-field="contact-info" data-click-to-select="false" data-filter-control="input" data-formatter="contactInfoColumnFormatter">Contacto</th>
      {{-- <th data-field="name" data-filter-control="input">Nombre de contacto</th> --}}
      {{-- <th data-field="firstname" data-filter-control="input">Apellido de contacto</th> --}}
      <th data-field="cellphone" data-filter-control="input">Teléfono de contacto</th>
      <th data-field="email" data-filter-control="input">Correo de contacto</th>
      {{-- <th data-field="logo" data-click-to-select="false" data-events="" data-formatter="logotypeColumnFormatter">Logotipo</th> --}}
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Editar</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>

{{-- modal Instituciones Educativas --}}
<div class="modal" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Institucion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{-- <form action="" enctype="multipart/form-data" onsubmit="saveinstitution(this); return false;"> --}}
      <form id="form" action="" enctype="multipart/form-data">
        <div class="modal-body">
            <input id="id" type="hidden" value="">

            <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::label('institution_name','Nombre de la institución:') !!}
                          {!! Form::text('institution_name',null,['class'=>'form-control','placeholder'=>'Nombre','autofocus']) !!}
                      </div>
                  </div>
            </div>
            <hr style="border-bottom: 2px solid rgb(0, 255, 21)">
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <p>Datos del Contacto</p>
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
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('institutional_charge','Cargo de la institución:') !!}
                      {!! Form::text('institutional_charge',null,['class'=>'form-control','placeholder'=>'Cargo']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('institutional_email','Correo institucional:') !!}
                      {!! Form::text('institutional_email',null,['class'=>'form-control','placeholder'=>'Correo']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('institutional_phone','telefono institucional:') !!}
                      {!! Form::text('institutional_phone',null,['class'=>'form-control','placeholder'=>'telefono']) !!}
                  </div>
              </div>
            </div>
            <hr style="border-bottom: 2px solid rgb(0, 255, 21)">
  
  
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('address','Direccion:') !!}
                      {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'Direccion',]) !!}
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
                      {{-- <input id="logotype" 
                        name="logotype" 
                        type="file" 
                        class="form-control" 
                        accept=".png,.jpg" 
                        placeholder="Logo tipo"> --}}
                      {!! Form::file('logotype',null,['accept'=>'.jpg,.png','class'=>'form-control','placeholder'=>'Logo tipo']) !!}
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
<script src="{{asset('js/educativeinstitutions.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection