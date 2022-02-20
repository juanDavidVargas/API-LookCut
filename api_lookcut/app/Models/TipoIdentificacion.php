<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIdentificacion extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tipo_identificacion';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'descripcion'
    ];
}
