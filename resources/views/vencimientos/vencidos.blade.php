<!-- productos/vencidos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="my-4">Productos Vencidos</h1>

    @if($productosVencidos->isEmpty())
        <p id="aviso">
            <i class="fa-regular fa-thumbs-up"></i> No hay productos vencidos
        </p>
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
                @foreach ($productosVencidos as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->fchVto }}</td>
                        <td>{{ $producto->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
