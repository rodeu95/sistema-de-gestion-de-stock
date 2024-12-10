<!-- productos/vencidos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="my-4">Productos Próximos a Vencer</h1>

    @if($productosProximosAVencer->isEmpty())
        <p>No hay productos próximos a vencerse</p>
    @else
        <table class="table shadow">
            <thead>
                <tr>
                    <th style="background-color:#ddd; color:#fff;">Nombre</th>
                    <th style="background-color:#ddd; color:#fff;">Fecha de Vencimiento</th>
                    <th style="background-color:#ddd; color:#fff;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productosProximosAVencer as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->fchVto}} </td>
                        <td>{{ $producto->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
