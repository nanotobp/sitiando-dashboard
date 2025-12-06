@extends('layout')

@section('content')
<h1 class="fw-bold text-white mb-4">Editar Producto</h1>

<form method="POST" action="/productos/{{ $producto->id }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nombre</label>
        <input class="form-control" name="nombre" value="{{ $producto->nombre }}" required>
    </div>

    <div class="mb-3">
        <label>Precio (Gs)</label>
        <input class="form-control" name="precio" type="number" step="0.01" value="{{ $producto->precio }}" required>
    </div>

    <div class="mb-3">
        <label>Stock</label>
        <input class="form-control" name="stock" type="number" value="{{ $producto->stock }}" required>
    </div>

    <div class="mb-3">
        <label>Imagen</label><br>
        @if($producto->imagen)
            <img src="{{ asset('storage/'.$producto->imagen) }}" width="120" class="rounded mb-2">
        @endif
        <input class="form-control" name="imagen" type="file">
    </div>

    <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
