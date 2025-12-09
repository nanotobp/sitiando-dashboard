<!DOCTYPE html>
<html>
<head>
    <title>Login - Sitiando PRO</title>
</head>
<body>

<h2>Ingresar</h2>

<form action="{{ route('login.submit') }}" method="POST">

    @csrf

    <label>Email</label>
    <input type="email" name="email">

    <label>Password</label>
    <input type="password" name="password">

    <button type="submit">Ingresar</button>
</form>

</body>
</html>
