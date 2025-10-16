<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientosInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'tipo_movimiento', 
        'cantidad',
        'fecha_movimiento',
        'realizado_por'
    ];

    
    public function producto()
    {
        return $this->belongsTo(Inventario::class, 'id_producto');
    }


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'realizado_por');
    }
}
