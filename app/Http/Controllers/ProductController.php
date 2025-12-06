<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $productos = Product::orderBy('id', 'desc')->paginate(12);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio', 'stock']);
        $data['activo'] = true;

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $path;
        }

        Product::create($data);

        return redirect('/productos')->with('ok', 'Producto creado correctamente.');
    }

    public function edit(Product $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Product $producto)
    {
        $request->validate([
            'nombre' => 'required|min:3',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $producto->update($request->only(['nombre', 'precio', 'stock']));

        if ($request->hasFile('imagen')) {

            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $path = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $path;
            $producto->save();
        }

        return redirect('/productos')->with('ok', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect('/productos')->with('ok', 'Producto eliminado.');
    }
}
