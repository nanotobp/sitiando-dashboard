<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitiando · Login</title>

    {{-- DASHFORGE CORE --}}
    <link rel="stylesheet" href="/dashforge.min.css">

    <style>
        body {
            background: #0f1117;
            color: #fff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* LEFT PANEL (IMAGE) */
        .login-left {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1600')
                center/cover no-repeat;
            display: flex;
            align-items: flex-end;
            padding: 40px;
        }

        .brand-text {
            font-size: 34px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,0,0,.6);
        }

        /* RIGHT PANEL (FORM) */
        .login-right {
            flex: 0 0 420px;
            background: #13151b;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            box-shadow: -5px 0 20px rgba(0,0,0,.4);
        }

        .login-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-sub {
            opacity: .7;
            margin-bottom: 30px;
        }

        .btn-primary {
            width: 100%;
            background: #3b7ddd;
            border-color: #3b7ddd;
        }

        .btn-primary:hover {
            background: #346fcc;
        }

        /* MOBILE */
        @media (max-width: 900px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
                width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    {{-- LEFT IMAGE PANEL --}}
    <div class="login-left">
        <div class="brand-text">Sitiando Dashboard</div>
    </div>

    {{-- RIGHT LOGIN PANEL --}}
    <div class="login-right">

        <div class="mb-4 text-center">
            <h1 class="login-title">Bienvenido</h1>
            <p class="login-sub">Accedé al panel general de Sitiando</p>
        </div>

        {{-- LOGIN FORM --}}
        <form method="POST" action="/login">
            @csrf

            {{-- EMAIL --}}
            <div class="form-group mb-3">
                <label>Email</label>
                <input class="form-control form-control-dark" type="email" name="email" required autofocus>
            </div>

            {{-- PASSWORD --}}
            <div class="form-group mb-4">
                <label>Contraseña</label>
                <input class="form-control form-control-dark" type="password" name="password" required>
            </div>

            {{-- ERROR MSG --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    Credenciales incorrectas.
                </div>
            @endif

            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>

    </div>
</div>

</body>
</html>
