<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'cantidad',
        'unidad_medida',
        'fecha_ingreso',
        'stock_minimo',
        'estado',         
        'imagen'         
    ];
}
