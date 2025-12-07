@extends('layouts.dashboard')

@section('content')

<div class="df-title-block">
    <h1 class="title">Productos</h1>
    <p class="subtitle-text">
        Catálogo disponible para la tienda. Podés crear, editar y ajustar stock.
    </p>
</div>

<div class="d-flex justify-content-between mb-3">
    <div></div>
    <a href="{{ route('admin.products.create') }}" class="topbar-btn">
        + Nuevo producto
    </a>
</div>

<div class="card table-card">
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} Gs</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Todavía no hay productos.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-3 py-2">
        {{ $products->links() }}
    </div>
</div>

@endsection