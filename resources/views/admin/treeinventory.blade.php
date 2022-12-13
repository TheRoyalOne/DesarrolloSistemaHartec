@extends('layouts.app')
@section('content')
@include('admin.treeinventoryEdit')
<div class="panel-heading">
    <h3 class="panel-title">Entradas de árboles </h3>   
</div>
<table
  class="table table-striped"
  id="table"
  data-id-field="id"
  data-filter-control="true"
  data-url="entries/show" 
  data-show-toggle="false"
  data-buttons-class="primary"
  data-buttons="buttons"
  data-show-search-clear-button="false"
  data-buttons-align="center"
  data-show-footer="true">
  <thead>
    <tr>
      <th data-field="id" data-filter-control="input">#</th>
      <th data-field="nursery" data-filter-control="input">Vivero</th>
      <th data-field="species" data-filter-control="input">Especie</th>
      <th data-field="amount" data-filter-control="input">Cantidad</th>
      <th data-field="age" data-filter-control="input">Edad</th>
      <th data-field="operate" data-click-to-select="false" data-events="operateEvents" data-formatter="operateFormatter">Editar</th>
      <th data-field="operate2" data-click-to-select="false" data-events="operateEvents2" data-formatter="operateFormatter2">Eliminar</th>
    </tr>
  </thead>
</table>
<div class="modal" tabindex="-1" role="dialog" id="add_Entrie">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Entrada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" onsubmit="saveentrance(this); return false;">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_nurserie">Vivero</label>
                        <select class="form-control" name="id_nurserie" id="" required>
                            <option value="">Seleccionar un Vivero</option>
                            @foreach ($nursery as $id=>$nurseries )
                                <option value="{{$id}}">{{$nurseries}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_species">Especie</label>
                        <select class="form-control" name="id_species" id="" required>
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
                    <label for="amount">Cantidad</label>
                    <input class="form-control" name="amount" type="text" required>
                </div>
                <div class="col-md-6">
                    <label for="age">Edad</label>
                    <input class="form-control" name="age" type="text" placeholder="Meses" required>
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
<script src="{{asset('js/treeinventory.js')}}"></script>
<script>
    var getURL = window.location;
    var baseURL = "{{url()->current()}}";
</script>
<script>
     var filterDefaults = {
            1: 'Activo',
            0: 'Inactivo'
        };
     $(function() {
        $('#table').bootstrapTable();
        $(".btn[name='clearSearch']").addClass('btn-secondary');
    });
    function buttons () {
    return {
      btnAdd: {
        text: 'Add new row',
        icon: 'fa-plus',
        event: function () {
          $("#add_Entrie").modal('show');
        },
        attributes: {
          title: 'Agregar'
        }
      }
      // btnPrint: {
      //   text: 'Imprimir',
      //   icon: 'fa-file-excel-o',
      //   event: function () {
      //     alert('Do some stuff to e.g. add a new row')
      //   },
      //   attributes: {
      //     title: 'Imprimir'
      //   }
      // }
    }
    }
</script>
@endsection