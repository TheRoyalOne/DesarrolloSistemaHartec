<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Paquete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          {!! Form::label('name1','Nombre del Paquete:') !!}
                          {!! Form::text('name1',null,['class'=>'form-control','placeholder'=>'Nombre','required','autofocus']) !!}
                      </div>
                  </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {{-- <div class="multiselect-content">
                  <div class="multiselect-input">
                      <label for="">Adopciones</label>
                      <div class="form-outline">
                          <input class="form-control form-control-select" type="text" id="form-control-select" value="TODOS" readonly>
                          <span class="form-arrow">
                              <img src="{{asset('img/arrow-down-sign-to-navigate.svg')}}" alt="" width="10px">
                          </span>
                          <ul class="list-select" id="list-select">
                              <li class="select-item">
                                  <label class="check-container">
                                      PENDIENTE
                                      <input type="checkbox" name="workshop[]" value="PENDIENTE">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      EN PROCESO
                                      <input type="checkbox" name="status[]" value="EN PROCESO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      CALENDARIZADO
                                      <input type="checkbox" name="status[]" value="CALENDARIZADO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                </div> --}}
                {!! Form::label('id_workshop_events1','Eventos Taller:') !!}
                {!! Form::select('id_workshop_events1',$workshop,null,['class'=>'form-control']) !!}
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('id_adoption_events1','Eventos Adopción:') !!}
                      {!! Form::select('id_adoption_events1',$adoption,null,['class'=>'form-control']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {{-- <div class="multiselect-content">
                  <div class="multiselect-input">
                      <label for="">Adopciones</label>
                      <div class="form-outline">
                          <input class="form-control form-control-select" type="text" id="form-control-select" value="TODOS" readonly>
                          <span class="form-arrow">
                              <img src="{{asset('img/arrow-down-sign-to-navigate.svg')}}" alt="" width="10px">
                          </span>
                          <ul class="list-select" id="list-select">
                              <li class="select-item">
                                  <label class="check-container">
                                      PENDIENTE
                                      <input type="checkbox" name="workshop[]" value="PENDIENTE">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      EN PROCESO
                                      <input type="checkbox" name="status[]" value="EN PROCESO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      CALENDARIZADO
                                      <input type="checkbox" name="status[]" value="CALENDARIZADO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                </div> --}}
                {!! Form::label('id_workshop_events11','Eventos Taller:') !!}
                {!! Form::select('id_workshop_events11',$workshop,null,['class'=>'form-control']) !!}
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('id_adoption_events11','Eventos Adopción:') !!}
                      {!! Form::select('id_adoption_events11',$adoption,null,['class'=>'form-control']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {{-- <div class="multiselect-content">
                  <div class="multiselect-input">
                      <label for="">Adopciones</label>
                      <div class="form-outline">
                          <input class="form-control form-control-select" type="text" id="form-control-select" value="TODOS" readonly>
                          <span class="form-arrow">
                              <img src="{{asset('img/arrow-down-sign-to-navigate.svg')}}" alt="" width="10px">
                          </span>
                          <ul class="list-select" id="list-select">
                              <li class="select-item">
                                  <label class="check-container">
                                      PENDIENTE
                                      <input type="checkbox" name="workshop[]" value="PENDIENTE">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      EN PROCESO
                                      <input type="checkbox" name="status[]" value="EN PROCESO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      CALENDARIZADO
                                      <input type="checkbox" name="status[]" value="CALENDARIZADO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                </div> --}}
                {!! Form::label('id_workshop_events21','Eventos Taller:') !!}
                {!! Form::select('id_workshop_events21',$workshop,null,['class'=>'form-control']) !!}
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('id_adoption_events21','Eventos Adopción:') !!}
                      {!! Form::select('id_adoption_events21',$adoption,null,['class'=>'form-control']) !!}
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {{-- <div class="multiselect-content">
                  <div class="multiselect-input">
                      <label for="">Adopciones</label>
                      <div class="form-outline">
                          <input class="form-control form-control-select" type="text" id="form-control-select" value="TODOS" readonly>
                          <span class="form-arrow">
                              <img src="{{asset('img/arrow-down-sign-to-navigate.svg')}}" alt="" width="10px">
                          </span>
                          <ul class="list-select" id="list-select">
                              <li class="select-item">
                                  <label class="check-container">
                                      PENDIENTE
                                      <input type="checkbox" name="workshop[]" value="PENDIENTE">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      EN PROCESO
                                      <input type="checkbox" name="status[]" value="EN PROCESO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                              <li class="select-item">
                                  <label class="check-container">
                                      CALENDARIZADO
                                      <input type="checkbox" name="status[]" value="CALENDARIZADO">
                                      <span class="checkmark"></span>
                                  </label>
                              </li>
                          </ul>
                      </div>
                  </div>
                </div> --}}
                {!! Form::label('id_workshop_events31','Eventos Taller:') !!}
                {!! Form::select('id_workshop_events31',$workshop,null,['class'=>'form-control']) !!}
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('id_adoption_events31','Eventos Adopción:') !!}
                      {!! Form::select('id_adoption_events31',$adoption,null,['class'=>'form-control']) !!}
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
          <button type="button" onclick="updatepackage()" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </div>
  </div>