<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;


class ProductoController extends Controller
{
    public function index(){
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'nombre', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $Productos = Producto::with('media')
            ->whereHas('categorias', function ($query) {
                if (request('search_categoria')) {
                    $categorias = explode(",", request('search_categoria'));
                    $query->whereIn('id', $categorias);
                }
            })
            ->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_nombre'), function ($query) {
                $query->where('nombre', 'like', '%' . request('search_nombre') . '%');
            })
            ->when(request('search_descripcion'), function ($query) {
                $query->where('descripcion', 'like', '%' . request('search_descripcion') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('nombre', 'like', '%' . request('search_global') . '%')
                        ->orWhere('descripcion', 'like', '%' . request('search_global') . '%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);
        return ProductoResource::collection($Productos);
    }
    public function store(Request $request)
    {

        $request->validate([
           'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'categoria_id' => 'required'
        ]);
        $producto = $request->all();
        $productos = Producto::create($producto);

        if ($request->hasFile('thumbnail')) {
            $productos->addMediaFromRequest('thumbnail')->preservingOriginal()->toMediaCollection('productoImg');
        }

        return new ProductoResource($productos);
        //return response()->json(['success' => true, 'data' => $producto]);
    }
    public function destroy($id, Request $request)
    {

        $producto = Producto::find($id);
        $producto->delete();

        return response()->json(['succes' => true, 'data' => "Deleted"]);

    }
    public function update($id, Request $request)
    {

        $producto = Producto::find($id);
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'categoria_id' => 'required'
        ]);

        $dateToUpdate = $request->all();
        $producto->update($dateToUpdate);

        return response()->json(['success' => true, 'data'=> $producto]);
    }
    public function edit($id)
    {
        $producto = Producto::find($id);

        return response()->json(['success' => true, 'data' => $producto]);
    }

    public function getCategoriaByProducto($id)
    {
        $productos = Producto::with('categorias')->where('categoria_id', $id)->paginate();

        return ProductoResource::collection($productos);
    }
    public function getProductos()
    {
        //$productos = Producto::all();
        $productos = Producto::with('categorias')->get();

        //$productos = producto::with('categorias')->latest()->paginate();
        return ProductoResource::collection($productos);

    }

    public function getproducto($id)
    {
        return producto::with('categorias')->findOrFail($id);
    }
}

