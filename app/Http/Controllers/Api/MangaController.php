<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMangaRequest;
use App\Http\Resources\MangaResource;
use App\Models\Categoria;
use App\Models\Manga;
use App\Models\ProgresoUsuario;
use Illuminate\Http\Request;


class MangaController extends Controller
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
        $Mangas = Manga::with('media')
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
        return MangaResource::collection($Mangas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'numero' => 'required',
            'descripcion' => 'required',
            'categoria_id' => 'required'
         ]);
         $Manga = $request->all();
         $Mangas = Manga::create($Manga);

        return response()->json(['success' => true, 'data' => $Manga]);
        //return new MangaResource($Manga);
    }

    public function show($id)
    {
        //return $id;

        $manga = Manga::find($id);
        //$this->authorize('manga-edit');
        //return new MangaResource($manga);
        return $manga;
        
    }


    //NO edita imagen
    public function update(Manga $Manga, StoreMangaRequest $request)
    {
        $this->authorize('manga-edit');
        
        $Manga->update($request->validated());
        //error_log(json_encode($request->categorias));
        $categoria = Categoria::findMany($request->categorias);
        //$Manga->categorias()->sync($categoria);
        return new MangaResource($Manga);
        
    }

    public function destroy($id)
    {        
        $this->authorize('manga-delete');

        $manga = Manga::find($id);
        $manga->delete();

        return response()->json(['succes' => true, 'data' => "Deleted"]);
        
    }

    public function getMangas()
    {
        $Mangas = Manga::with('categorias')->with('media')->latest()->paginate();
        return MangaResource::collection($Mangas);

    }

    public function getCategoriaByManga($id)
    {
        $Mangas = Manga::whereRelation('categorias', 'categoria_id', '=', $id)->paginate();

        return MangaResource::collection($Mangas);
    }

    public function getManga($id)
    {
        return Manga::with('categorias', 'progresos', 'media')->findOrFail($id);
    }

    public function upload(Request $request, $id) {
        $manga = Manga::find($id);
    
        if ($request->hasFile('pagina')) {
            $files = $request->file('pagina');
    
            foreach ($files as $file) {
                $manga->addMedia($file)->toMediaCollection('mangaImg');
            }

            // Devolver una respuesta exitosa
            return response()->json([
                'message' => 'Archivos subidos correctamente',
                'request_data' => $request
            ], 200);
        }
                
        // Devolver una respuesta de error si no se encontraron archivos
        return response()->json(['error' => 'No se han encontrado archivos para subir'], 400);
    }

}
