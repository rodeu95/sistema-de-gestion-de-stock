@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <h3 class="mb-4">Registrar Venta</h3>
    
    <!-- Mostrar mensaje de advertencia si la caja está cerrada -->
    @if(!$cajaAbierta)
        <div class="alert alert-danger">
            La caja está cerrada. No puedes registrar ventas en este momento.
        </div>
    @endif

    <!-- Formulario de Registro de Venta -->
    <form action="{{ route('ventas.store') }}" method="POST" id="ventasForm">
        @csrf
        <div class="row">

            <input 
                type="hidden" 
                id="codigo-input" 
                class="form-control" 
                name="producto_cod"
            >
                            
            <input 
            id="producto-nombre"
            type="hidden"
            class="form-control"
            readonly
            >

            <input 
                type="hidden" 
                id="cantidad-input" 
                class="form-control" 
                name="cantidad" 
                value="" 
            >

            <input type="hidden" id="producto-stock">
            <input type="hidden" id="producto-precio">
            <input type="hidden" id="producto-unidad">

            <button type="button" id="add-product" class="btn btn-secondary mb-3" @if(!$cajaAbierta) disabled @endif style="background-color: grey; display: none;">Agregar Producto</button>
                    
                
            <div class="col-lg-6">
                <!-- Sección de productos seleccionados -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 justify-content text-center" style="text-shadow: none;">
                            <div class="icon-box">
                                <i class="fa-solid fa-list"></i>
                            </div>
                            Productos Seleccionados
                        </h5>
                    </div>
                    <div class="card-body">
                        <section class="bg-light p-4 rounded shadow section-index">
                            <ul id="product-list" class="list-group mb-3">
                                <!-- Aquí se agregarán los productos seleccionados -->
                            </ul>

                            <div id="hidden-inputs"></div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Sección de monto total -->
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0 justify-content text-center" style="text-shadow: none;">
                            <div class="icon-box">
                                <i class="fa-solid fa-dollar"></i>
                            </div>
                            Monto Total
                        </h5>
                    </div>
                    <div class="card-body">
                        <section class="bg-light p-4 rounded shadow section-index">
                            <div class="mb-3">
                                <label for="monto_total" class="form-label">Monto Total</label>
                                <input type="number" name="monto_total" id="monto_total" class="form-control form-control-xl" style="font-size: 2.5rem;" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                <select class="form-select" name="metodo_pago_id" id="metodo_pago_id">
                                    <option value="" disabled selected>Seleccione un método de pago</option>
                                    @foreach ($metodosdepago as $metododepago)
                                        <option value="{{$metododepago->id}}">{{$metododepago->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn" style="margin-right:10px" @if(!$cajaAbierta) disabled @endif> Agregar Venta </button>
                                <a href="javascript:history.back()" class="btn btn-cancelar">Cancelar</a>
                            </div>
                        </section>
                        
                    </div>
                </div>
            </div>
        </div>
    </form>          
</div>
<!-- Modal para editar la cantidad -->
<div class="modal fade" id="editQuantityModal" tabindex="-1" role="dialog" aria-labelledby="editQuantityModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editQuantityModalLabel">Editar Cantidad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="newQuantity" class="form-label">Nueva Cantidad:</label>
        <input type="number" id="newQuantity" class="form-control" min="0.1" step="0.1" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" id="confirmEditQuantity">Guardar</button>
      </div>
    </div>
  </div>
</div>



@push('js')
<script src="{{ asset('js/ventas.js') }}"></script>

<script>
    const fecha = new Date();
    fecha.setMinutes(fecha.getMinutes() - fecha.getTimezoneOffset()); // Ajusta la zona horaria
    document.getElementById("fecha_venta").value = fecha.toISOString().split("T")[0];

</script>

@endpush
@endsection
