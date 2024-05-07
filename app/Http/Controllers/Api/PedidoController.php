<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index(){
        // return "Hola";
        $pedidos = Pedido::with('productos')->get(); //con esta funcion podemos guardar todas las tareas de la base de datos en un array

        return $pedidos;
    }
    public function store(Request $request)
    {

        // Valida los datos del pedido
        $validatedData = $request->validate([
            'precioFinal' => 'required'
        ]);
        $validatedData['usuario_id'] = auth()->id();

        // Crea el pedido
        $pedido = Pedido::create($validatedData);
    
        // Obtén los productos del carrito de la sesión
        $productosEnCarrito = $request->session()->get('productos', []);
    
        // Itera sobre los productos en el carrito
        foreach ($productosEnCarrito as $productoCarrito) {
            // Agrega el producto al pedido y a la tabla intermedia
            $pedido->productos()->attach($productoCarrito['producto'], ['cantidad' => $productoCarrito['cantidad']]);
        }
    
        // Limpia el carrito de la sesión
        $request->session()->forget('productos');
    
        // Retorna una respuesta con los datos del pedido
        return response()->json(['success' => true, 'data' => $pedido]);
    }
    
    public function destroy($id, Request $request)
    {

        $producto = Pedido::find($id);
        $producto->delete();

        return response()->json(['succes' => true, 'data' => "Deleted"]);

    }
    public function update($id, Request $request)
    {

        $producto = Pedido::find($id);
        $request->validate([
            'usuario_id' => 'required',
            'precioFinal' => 'required'
        ]);

        $dateToUpdate = $request->all();
        $producto->update($dateToUpdate);

        return response()->json(['success' => true, 'data'=> $producto]);
    }
    public function edit($id)
    {
        $producto = Pedido::find($id);

        return response()->json(['success' => true, 'data' => $producto]);
    }

    public function misPedidos(){
        $usr = auth()->id();
        $pedidos = Pedido::with('productos')->where('usuario_id', $usr)->get(); 
    
        return $pedidos;
    }
    
}


