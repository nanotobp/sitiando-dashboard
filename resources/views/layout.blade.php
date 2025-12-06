<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sitiando Dashboard</title>

    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/sitiando.css') }}">

    {{-- CSS exclusivo del dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="dashboard-body">

    {{-- HEADER --}}
    <header class="dashboard-header">
        <div class="header-left">
            <h1>Sitiando</h1>
        </div>

        <div class="header-right">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn">Cerrar sesión</button>
            </form>
        </div>
    </header>

    {{-- CONTENIDO --}}
    <main class="dashboard-main">
        @yield('content')
    </main>

</body>
</html>
