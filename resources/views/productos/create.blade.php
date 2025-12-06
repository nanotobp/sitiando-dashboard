@extends('layout')

@section('content')
<h1 class="fw-bold text-white mb-4">Nuevo Producto</h1>

<form method="POST" action="/productos" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Nombre</label>
        <input class="form-control" name="nombre" required>
    </div>

    <div class="mb-3">
        <label>Precio (Gs)</label>
        <input class="form-control" name="precio" type="number" step="0.01" required>
    </div>

    <div class="mb-3">
        <label>Stock</label>
        <input class="form-control" name="stock" type="number" required>
    </div>

    <div class="mb-3">
        <label>Imagen</label>
        <input class="form-control" name="imagen" type="file">
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>
@endsection
