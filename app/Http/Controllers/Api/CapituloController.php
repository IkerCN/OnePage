<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCapituloRequest;
use App\Http\Resources\CapituloResource;
use App\Models\Categoria;
use App\Models\ProgresoUsuario;
use App\Models\Capitulo;


class CapituloController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'titulo', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $Capitulos = Capitulo::with('media')
            ->whereHas('categorias', function ($query) {
                if (request('search_categoria')) {
                    $categorias = explode(",", request('search_categoria'));
                    $query->whereIn('id', $categorias);
                }
            })
            ->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
            })
            ->when(request('search_titulo'), function ($query) {
                $query->where('titulo', 'like', '%' . request('search_titulo') . '%');
            })
            ->when(request('search_descripcion'), function ($query) {
                $query->where('descripcion', 'like', '%' . request('search_descripcion') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('titulo', 'like', '%' . request('search_global') . '%')
                        ->orWhere('descripcion', 'like', '%' . request('search_global') . '%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);
        return CapituloResource::collection($Capitulos);
    }

    public function store(StoreCapituloRequest $request)
    {
        $validatedData = $request->validated();
        $Capitulo = Capitulo::create($validatedData);

        if ($request->hasFile('thumbnail')) {
            $Capitulo->addMediaFromRequest('thumbnail')->preservingOriginal()->toMediaCollection('videos');
        }
        return response()->json(['success' => true, 'data' => $Capitulo]);
        //return new CapituloResource($Capitulo);
    }

    public function show($id)
    {
        $this->authorize('capitulo-edit');
        $capitulo = Capitulo::find($id);
        return new CapituloResource($capitulo);
        
    }


    //NO edita imagen
    public function update(Capitulo $Capitulo, StoreCapituloRequest $request)
    {
        $this->authorize('capitulo-edit');
        

        $Capitulo->update($request->validated());
        //error_log(json_encode($request->categorias));
        $categoria = Categoria::findMany($request->categorias);
        //$Capitulo->categorias()->sync($categoria);
        return new CapituloResource($Capitulo);
        
    }

    public function destroy(Capitulo $Capitulo)
    {
        $this->authorize('capitulo-delete');
        if (!auth()->user()->hasPermissionTo('Capitulo-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only delete your own Capitulos']);
        } else {
            $Capitulo->delete();
            return response()->noContent();
        }
    }

    public function getCapitulos()
    {
        $Capitulos = Capitulo::with('categorias')->with('media')->latest()->paginate();
        return CapituloResource::collection($Capitulos);

    }

    public function getCategoriaByCapitulo($id)
    {
        $Capitulos = Capitulo::whereRelation('categorias', 'categoria_id', '=', $id)->paginate();

        return CapituloResource::collection($Capitulos);
    }

    public function getCapitulo($id)
    {
        return Capitulo::with('categorias', 'progresos', 'media')->findOrFail($id);
    }
}