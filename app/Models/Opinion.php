<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    protected $table      = 'opiniones';
    protected $primaryKey = 'id_opinion';
    public $timestamps    = false; 

    protected $fillable = ['id_cliente','calificacion','comentario','fecha'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}
