<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mesa;
use App\Models\Cliente;

class Reservacion extends Model
{
    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_mesa',
        'fecha',
        'hora',
        'num_personas',
        'estado'
    ];


    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

 
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
