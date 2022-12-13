@extends('layouts.app')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title">Entrega de Árboles</h3>
</div>

{{-- <button type="button" onclick="" class="btn btn-primary">Agregar</button> --}}
{{-- <a type="button" onclick="" class="btn btn-primary"><span style="color: white">Agregar</span></a> --}}

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
      {{--<th data-field="id" data-filter-control="input">No.</th> --}}
      <th data-field="event_name" data-filter-control="input">Evento</th>
      {{--<th data-field="nursery_name" data-filter-control="input">Vivero</th>--}}
      <th data-field="sponsor_name" data-filter-control="input">Contacto</th>
      <th data-field="thecnical_user_name" data-filter-control="input">Responsable</th>
      <th data-field="adoption_date" data-filter-control="input">Fecha de salida</th>
      <th data-field="edit" data-click-to-select="false" data-events="editColumnEvent" data-formatter="editColumnFormatter">Ver</th>
      <th data-field="delete" data-click-to-select="false" data-events="deleteColumnEvent" data-formatter="deleteColumnFormatter">Eliminar</th>
    </tr>
  </thead>
</table>
@endsection

@section('js')
<script src="{{asset('js/tree-deliveries.js')}}"></script>
<script>
  var getURL = window.location;
  var baseURL = "{{ url()->current() }}";

  $(function() {
    initializeTable();
    $(".btn[name='clearSearch']").addClass('btn-secondary');
  });
</script>
@endsection