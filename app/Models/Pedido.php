<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = [
        "usuario_id",
        "precioFinal"
    ];
    public function productos()
    {
        return $this->belongsToMany(Producto::class,'pedido_productos');
    }
}
