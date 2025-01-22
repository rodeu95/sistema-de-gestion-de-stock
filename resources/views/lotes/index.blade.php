@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="my-4">Lista de Lotes</h1>
</div>

<main class="container-lg">
    <div id="lotes-table"></div>
    <div id="deleteButtonTemplate" style="display: none;">
        @can('gestionar-inventario')
            <button type="button" title="Eliminar lote" class="btn btn-sm btn-delete-lote" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        @endcan
    </div>
</main>

@push('js')
<script src="{{ asset('js/lotes/eliminar.js') }}"></script>
<script>
    var loteIndexUrl = "{{ route('api.lotes.index') }}";
    var eliminarLoteUrl = "{{ route('api.lotes.destroy', 'id')}}";
</script>
@endpush
@endsection
