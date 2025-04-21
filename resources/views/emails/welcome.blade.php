<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a LA GRAN TIENDA</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); padding: 20px;">
        <div style="text-align: center; padding: 10px;">
            <img src="https://i.imgur.com/pFNtYgr.jpeg" alt="Logo" style="max-width: 150px; height: auto;">
        </div>
        <div style="text-align: center; color: #333;">
            <h1 style="font-size: 24px;">¡Bienvenid@, {{ $user->name }}!</h1>
            <p>Te has registrado en el sistema de gestión de ventas e inventario de <strong>LA GRAN TIENDA</strong>.</p>
            <p>Por favor, asegúrate de que tu administrador te conceda los permisos necesarios.</p>
            <a href="{{ url('/inicio') }}" style="display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #acd8b5; color: #000; text-decoration: none; border-radius: 5px;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Ir a la app</a>
        </div>
        <div style="margin-top: 20px; text-align: center; font-size: 0.9em; color: #777;">
            <hr>
            <p>¡A trabajar!</p>
        </div>
    </div>
</body>
</html>
