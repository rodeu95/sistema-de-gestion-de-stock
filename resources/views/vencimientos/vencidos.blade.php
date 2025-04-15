<!-- productos/vencidos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="my-4">Productos Vencidos</h1>

    @if($loteVencido->isEmpty())
        <p id="aviso">
            <i class="fa-regular fa-thumbs-up"></i> No hay productos vencidos
        </p>
    @else
        <table class="table shadow table-responsive">
            <thead>
                <tr>
                    <th style="background-color:#fff; color:grey;">Código</th>
                    <th style="background-color:#fff; color:grey;">Nombre</th>
                    <th style="background-color:#fff; color:grey;">Fecha de Vencimiento</th>
                    <th style="background-color:#fff; color:grey;">Número de lote</th>
                    <th style="background-color:#fff; color:grey;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loteVencido as $lote)
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
    @endif
@endsection
