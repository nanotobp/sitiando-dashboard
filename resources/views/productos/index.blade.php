@extends('layouts.dashboard')


@section('content')

<h1 class="fw-bold text-white mb-4">Productos</h1>

@if(session('ok'))
<div class="alert alert-success">{{ session('ok') }}</div>
@endif

<a href="/productos/create" class="btn btn-primary mb-3">+ Nuevo Producto</a>

<div class="row">
    @foreach($productos as $p)
    <div class="col-md-3 mb-4">
        <div class="card card-dark p-3">
            @if($p->imagen)
                <img src="{{ asset('storage/'.$p->imagen) }}" class="img-fluid mb-2 rounded">
            @endif

            <h5>{{ $p->nombre }}</h5>
            <p class="text-muted">Gs {{ number_format($p->precio, 0, ',', '.') }}</p>
            
            <a href="/productos/{{ $p->id }}/edit" class="btn btn-info btn-sm">Editar</a>

            <form method="POST" action="/productos/{{ $p->id }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('¿Eliminar?')" class="btn btn-danger btn-sm">Borrar</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{ $productos->links() }}

@endsection
