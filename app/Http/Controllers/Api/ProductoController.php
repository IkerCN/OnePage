<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\ProductoResource;
use Illuminate\Support\Facades\Session;


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
        $this->authorize('producto-edit');
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
        $this->authorize('producto-edit');

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
        return Producto::with('categorias', 'media')->findOrFail($id);
    }

    public function agregarAlCarrito(Request $request)
    {
        // Obtener el nombre del producto que deseas agregar al carrito desde el request
        $producto = $request->input('producto');
    
        // Obtener el array de productos de la sesión
        $carrito = $request->session()->get('productos', []);
    
        // Verificar si el producto ya existe en el array
        $productoEnCarrito = false;
        foreach ($carrito as &$productoCarrito) { // Usamos '&' para poder modificar directamente el elemento del array
            if ($productoCarrito['producto'] == $producto['id']) {
                // Si existe, incrementar la cantidad en 1
                $productoCarrito['cantidad']++;
                $productoEnCarrito = true;
                break;
            }
        }
    
        // Si el producto no estaba en el carrito, agregarlo con cantidad 1
        if (!$productoEnCarrito) {
            $carrito[] = [
                'producto' => $producto['id'],
                'cantidad' => 1
            ];
        }
    
        // Guardar el array actualizado en la sesión
        $request->session()->put('productos', $carrito);
    
        // Respondemos con los datos de los productos añadidos
        return response()->json(['message' => 'Producto agregado al carrito', 'productos' => $carrito]);
    }
    
    public function verCarrito(Request $request)
    {
        // Obtener los productos almacenados en la sesión
        $productosEnCarrito = $request->session()->get('productos', []);
        
        // Obtener los detalles completos de los productos en base a sus IDs
        $detallesProductos = [];
        foreach ($productosEnCarrito as $item) {
            $producto = Producto::with('categorias', 'media')->findOrFail($item['producto']);
            if ($producto) {
                $detallesProductos[] = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad']
                ];
            }
        }
        
        // Devolver los productos con sus detalles en una respuesta JSON
        return response()->json(['productosEnCarrito' => $detallesProductos]);
    }
    
    public function vaciarCarrito(Request $request)
    {
        // Eliminar todos los datos del carrito de la sesión
        $request->session()->forget('productos');

        // Respondemos con un mensaje indicando que el carrito ha sido vaciado
        return response()->json(['message' => 'Carrito vaciado exitosamente']);
    }

    public function eliminarProductoCarrito(Request $request)
    {
        // Obtener el ID del producto a eliminar desde la solicitud
        $productoId = $request->input('producto_id');
        
        // Obtener el array de productos de la sesión
        $productosEnCarrito = $request->session()->get('productos', []);
        
        // Buscar el producto en el array y eliminarlo
        foreach ($productosEnCarrito as $key => $producto) {
            if ($producto['producto'] == $productoId) {
                unset($productosEnCarrito[$key]);
                break;
            }
        }
    
        // Reindexar el array para que los índices sean secuenciales
        $productosEnCarrito = array_values($productosEnCarrito);
    
        // Guardar el array actualizado en la sesión
        $request->session()->put('productos', $productosEnCarrito);
    
        // Respondemos con un mensaje indicando que el producto ha sido eliminado
        return response()->json(['message' => 'Producto eliminado del carrito']);
    }

    public function actualizarCantidadProductoCarrito(Request $request)
    {
        // Obtener el ID del producto y la nueva cantidad desde la solicitud
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad');
        
        // Obtener el array de productos de la sesión
        $productosEnCarrito = $request->session()->get('productos', []);
        
        // Buscar el producto en el array y actualizar su cantidad
        foreach ($productosEnCarrito as &$producto) {
            if ($producto['producto'] == $productoId) {
                $producto['cantidad'] = $cantidad;
                break;
            }
        }
    
        // Guardar el array actualizado en la sesión
        $request->session()->put('productos', $productosEnCarrito);
    
        // Respondemos con un mensaje indicando que la cantidad ha sido actualizada
        return response()->json(['message' => 'Cantidad actualizada en el carrito']);
    }

}

