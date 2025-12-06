<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitiando Dashboard</title>

    {{-- CSS externo --}}
    <link rel="stylesheet" href="/css/sitiando.css">

    {{-- Dashforge (opcional, si querés estilos similares al theme) --}}
    <link rel="stylesheet" href="https://jenil.github.io/dashforge/css/dashforge.css">
</head>

<body>

<div class="wrapper">

    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Sitiando</h2>
        <a href="/dashboard">Dashboard</a>
        <a href="/productos">Productos</a>
        <form action="/logout" method="POST">
            @csrf
            <button class="logout-btn" style="background:none;border:none;padding:0;margin-top:20px;">
                Cerrar sesión
            </button>
        </form>
    </div>

    {{-- Barra superior --}}
    <div class="topnav">
        <span>Panel de Control</span>
    </div>

    {{-- Contenido dinámico --}}
    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>
