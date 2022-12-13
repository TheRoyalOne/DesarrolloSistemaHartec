<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Evento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {!!Form::hidden('id', null, ['id'=>'id'])!!}

            <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          {!! Form::label('name1','Nombre de Evento:') !!}
                          {!! Form::text('name1',null,['class'=>'form-control','placeholder'=>'Evento','required','autofocus']) !!}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          {!! Form::label('prefix_code1','Prefijo para código:') !!}
                          {!! Form::text('prefix_code1',null,['class'=>'form-control','placeholder'=>'Prefijo','required']) !!}
                      </div>
                  </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('trees1','# Árboles:') !!}
                      {!! Form::text('trees1',null,['class'=>'form-control','placeholder'=>'#','required']) !!}
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('recovery_fee1','Cuota recuperación:') !!}
                      {!! Form::text('recovery_fee1',null,['class'=>'form-control','placeholder'=>'Cuota','required']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      {!! Form::label('description1','Descripción:') !!}
                      {!! Form::textarea('description1',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                  </div>
              </div>
             </div>
            
        </div>
  
          {!! Form::close() !!}
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="updateadeption()" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </div>
  </div>