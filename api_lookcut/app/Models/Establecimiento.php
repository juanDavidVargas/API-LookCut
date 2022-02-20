<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    protected $connection = 'mysql';
    protected $table = 'establecimiento';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'descripcion', 'nit', 'ciudad_id', 'direccion', 'telefono',
        'correo', 'latitud', 'longitud', 'tipo_negocio_id'
    ];
}
