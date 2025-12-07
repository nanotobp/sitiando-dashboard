@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Nuevo producto</h1>
    <p class="subtitle-text">
        Cargá la información básica del producto.
    </p>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf

        <div class="mb-3">
            <label class="filter-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="filter-label">Precio</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}">
        </div>

        <div class="mb-3">
            <label class="filter-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}">
        </div>

        <div class="mb-3">
            <label class="filter-label">Descripción</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <button class="topbar-btn" type="submit">Guardar</button>
    </form>
</div>

@endsection