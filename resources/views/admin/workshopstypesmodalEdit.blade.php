<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Taller</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          {!! Form::label('name1','Nombre del Taller:') !!}
                          {!! Form::text('name1',null,['class'=>'form-control','placeholder'=>'Taller','required','autofocus']) !!}
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          {!! Form::label('description1','Descripción:') !!} 
                          {!! Form::text('description1',null,['class'=>'form-control','placeholder'=>'Descripción','required']) !!}
                      </div>
                  </div>
            </div>
        </div> 
          {!! Form::close() !!}
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" onclick="updatetype()" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </div>
  </div>