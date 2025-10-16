<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Notiresetdecontraseña;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'usuario',
        'correo',
        'contrasena',
        'id_rol',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->correo;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notiresetdecontraseña($token));
    }
}
