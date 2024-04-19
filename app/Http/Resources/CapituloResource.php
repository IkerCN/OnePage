<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

class CapituloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //if no resize image
        try {
            $resized_image = $this->getMedia('*');//[0]->getUrl('resized-image');
        } catch (Exception $e) {
            $resized_image="";
        }
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'numero' => $this->numero,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria_id,
            'original_image' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,

            'created_at' => $this->created_at->toDateString()
        ];
    }
}
