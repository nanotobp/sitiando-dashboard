<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitiando Dashboard</title>

    {{-- Dashforge Core --}}
    <link rel="stylesheet" href="/dashforge.min.css">

    <style>
        body {
            background: #0f1117;
            color: #fff;
            overflow-x: hidden;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .sidebar {
            width: 240px;
            background: #16191f;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
            transition: width .25s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            color: #adb5bd;
        }

        .sidebar .menu-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .sidebar.collapsed .menu-label {
            opacity: 0;
            width: 0;
        }

        .content {
            margin-left: 240px;
            padding: 30px;
            transition: margin-left .25s;
        }

        .sidebar.collapsed ~ .content {
            margin-left: 80px;
        }

        .header {
            background: #13151b;
            padding: 15px 30px;
            margin-bottom: 20px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .collapse-btn {
            cursor: pointer;
            font-size: 22px;
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- CONTENT AREA --}}
    <div class="content">

        {{-- HEADER --}}
        @include('partials.header')

        {{-- MAIN CONTENT --}}
        @yield('content')

    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const collapseBtn = document.querySelector('#collapseSidebar');

        collapseBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>

</body>
</html>
