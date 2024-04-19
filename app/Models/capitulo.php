<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class capitulo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['numero', 'titulo', 'descripcion','categoria_id'];

    public function progresos()
    {
        return $this->morphMany(progreso_usuarios::class, 'progresable');
    }
    public function categorias()
    {
        return $this->belongsTo(Categoria::class,'categoria_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/quicktime'])
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }

}

