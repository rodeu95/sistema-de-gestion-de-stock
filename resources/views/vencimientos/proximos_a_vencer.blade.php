<!-- productos/vencidos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="my-4">Productos Próximos a Vencer</h1>

    @if($lotesProximosAVencer->isEmpty())
        <p id="aviso">
            <i class="fa-regular fa-thumbs-up"></i> No hay productos próximos a vencerse
        </p>
    @else
    <div class="table-wrapper mb-3 shadow">
        <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Código</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Nombre</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Fecha de Vencimiento</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Número de lote</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lotesProximosAVencer as $lote)
                        <tr>
                            <td>{{ $lote->producto->codigo }}</td>
                            <td>{{ $lote->producto->nombre }}</td>
                            <td>{{ $lote->fecha_vencimiento}} </td>
                            <td>{{ $lote->numero_lote }}</td>
                            <td>{{ $lote->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
