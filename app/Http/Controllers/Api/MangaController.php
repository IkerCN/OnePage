<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMangaRequest;
use App\Http\Resources\MangaResource;
use App\Models\Categoria;
use App\Models\Manga;
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

        if ($request->hasFile('pagina')) {
            $fileAdders = $Mangas->addMultipleMediaFromRequest(['pagina'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('images/mangas');
                });
        }

        return response()->json(['success' => true, 'data' => $Manga]);
        //return new MangaResource($Manga);
    }

    public function show(Manga $Manga)
    {
        $this->authorize('manga-edit');
        if ($Manga->user_id !== auth()->user()->id) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own Mangas']);
        } else {
            return new MangaResource($Manga);
        }
    }


    //NO edita imagen
    public function update(Manga $Manga, StoreMangaRequest $request)
    {
        $this->authorize('manga-edit');
        
        if ($Manga->user_id !== auth()->id() ) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only edit your own Mangas']);
        } else {
            $Manga->update($request->validated());
            //error_log(json_encode($request->categorias));

            $categoria = Categoria::findMany($request->categorias);
            $Manga->categorias()->sync($categoria);

            return new MangaResource($Manga);
        }
    }

    public function destroy(Manga $Manga)
    {
        $this->authorize('manga-delete');
        if ($Manga->user_id !== auth()->id() && !auth()->user()->hasPermissionTo('Manga-all')) {
            return response()->json(['status' => 405, 'success' => false, 'message' => 'You can only delete your own Mangas']);
        } else {
            $Manga->delete();
            return response()->nodescripcion();
        }
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

    public function upload(Manga $Manga,Request $request)
    {
        // Verificar si se han enviado archivos
        if ($request->hasFile('pagina')) {
            // Obtener los archivos
            $files = $request->file('pagina');
            
            // Ver el contenido del request
            $requestData = $request->all();
            // O puedes acceder a campos específicos del request
            // $specificField = $request->input('fieldName');

            // Ver el contenido del request en formato JSON
            // Esto es útil para depurar y ver qué datos se están enviando
            $requestJson = json_encode($requestData);

            // Iterar sobre cada archivo
            foreach ($files as $file) {
                // Acceder a los detalles del archivo
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize(); // Tamaño en bytes
                $mime = $file->getMimeType(); // Tipo MIME del archivo

                // Puedes realizar otras operaciones aquí, como almacenar el nombre del archivo en la base de datos
                if ($request->hasFile('pagina')) {
                    $fileAdders = $Manga->addMultipleMediaFromRequest(['pagina'])
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('images/mangas');
                        });
                }
            }
            
            // Devolver una respuesta exitosa
            return response()->json([
                'message' => 'Archivos subidos correctamente',
                'request_data' => $requestJson
            ], 200);
        }
        
        // Devolver una respuesta de error si no se encontraron archivos
        return response()->json(['error' => 'No se han encontrado archivos para subir'], 400);
    }
}
