@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="text-center my-4">Inventario de Productos</h1>
</div>

<main class="container-lg">
    <form id="exportInventario" method="GET" action="{{ route('export.inventario') }}">

        <div class="row">
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-export"><i class="fa-solid fa-file-pdf"></i> Exportar Inventario</button>
            </div>
        </div>
    </form>

    <div id="inventario"></div>

    
</main>
<script src="{{ asset('js/inventario/index.js') }}"></script>
<script>
    var inventarioIndexUrl= "{{ route('api.inventario.index') }}";
    console.log(inventarioIndexUrl);
</script>
@endsection
