<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirigiendo al pago…</title>
</head>
<body onload="document.getElementById('bancardForm').submit();">

<h2>Redirigiéndote a Bancard, aguarda un momento…</h2>

<form id="bancardForm" method="POST" action="{{ $formUrl }}">
    @foreach ($payload as $key => $value)
        @if(is_array($value))
            @foreach ($value as $k => $v)
                <input type="hidden" name="{{ $key }}[{{ $k }}]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>

</body>
</html>
