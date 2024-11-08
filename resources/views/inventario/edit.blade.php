@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Actualizar Inventario</h2>
    
    <form action="{{ route('inventario.update') }}" method="POST">
        @csrf

        <div id="products-container">
            <div class="product-entry row g-3 mb-3">
                <div class="col-md-8">
                    <select name="producto_cod[]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->codigo }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="cantidad[]" class="form-control" placeholder="Cantidad" min="1" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn shadow btn-secondary" onclick="addProductEntry()">
                Añadir otro producto
            </button>
            <button type="submit" class="btn shadow" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Actualizar inventario</button>
        </div>
    </form>
</div>

<script>
    function addProductEntry() {
        const container = document.getElementById('products-container');
        const entry = document.querySelector('.product-entry').cloneNode(true);

        // Reset the cloned fields
        entry.querySelector('select').selectedIndex = 0;
        entry.querySelector('input').value = '';

        container.appendChild(entry);
    }
</script>
@endsection
