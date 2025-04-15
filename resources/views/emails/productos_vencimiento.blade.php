<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LA GRAN TIENDA</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
<div style="max-width: auto; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); padding: 20px;">
    <div style="text-align: center; padding: 10px;">
        <img src="https://i.imgur.com/pFNtYgr.jpeg" alt="Logo" style="max-width: 150px; height: auto;">
    </div>
    <h2 style="color: #d9534f; text-align: center;">⚠ Productos Próximos a Vencer ⚠</h2>
    <p style="color: #333; font-size: 16px;">Los siguientes productos están próximos a vencer. Por favor, revisa el inventario:</p>
    
    <div>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="background: #aed5b6; color: #fff; padding: 10px; text-align: left;">Producto</th>
                    <th style="background: #aed5b6; color: #fff; padding: 10px; text-align: left;">Lote</th>
                    <th style="background: #aed5b6; color: #fff; padding: 10px; text-align: left;">Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    @if ($producto->lotes->isNotEmpty())
                        @foreach($producto->lotes as $lote)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 10px;">{{ $producto->nombre }}</td>
                                <td style="border: 1px solid #ddd; padding: 10px;">{{ $lote->numero_lote }}</td>
                                <td style="border: 1px solid #ddd; padding: 10px; color: red; font-weight: bold;">
                                    {{ \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 10px;">{{ $producto->nombre }}</td>
                            <td colspan="2" style="border: 1px solid #ddd; padding: 10px; color: red;">
                                No tiene lotes próximos a vencer.
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div>
        <p style="margin-top: 20px; text-align: center;">
            <strong>📌 Recuerda revisar el inventario y tomar las acciones necesarias.</strong>
        </p>

    </div>
</div>
</body>

