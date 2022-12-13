<div class="modal" tabindex="-1" role="dialog" id="edit_Entrie">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Actualizar Entrada</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" onsubmit="updateentrance(this); return false;">
            <input type="hidden" name="id" id="entry_id">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="id_nurserie_edit">Vivero</label>
                          <select class="form-control" name="id_nurserie_edit" id="id_nurserie_edit" required>
                              <option value="">Seleccionar un Vivero</option>
                              @foreach ($nursery as $id=>$nurseries )
                                  <option value="{{$id}}">{{$nurseries}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="id_species_edit">Especie</label>
                          <select class="form-control" name="id_species_edit" id="id_species_edit" required>
                              <option value="">Seleccionar una Especie</option>
                              @foreach ($species as $id=> $specie )
                                  <option value="{{$id}}">{{$specie}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6">
                      <label for="amount_edit">Cantidad</label>
                      <input class="form-control" name="amount_edit" id="amount_edit" type="text" required placeholder="# de áboles">
                  </div>
                  <div class="col-md-6">
                      <label for="age_edit">Edad</label>
                      <input class="form-control" name="age_edit" id="age_edit" type="text" placeholder="Meses" required>
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