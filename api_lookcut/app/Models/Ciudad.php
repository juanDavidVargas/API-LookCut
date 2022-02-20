<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $connection = 'mysql';
    protected $table = 'ciudades';
    protected $primaryKey = 'id_ciudad';
    public $timestamps = true;
    protected $fillable = [
        'id_departamento', 'ciu_codigo_dane', 'ciu_descripcion', 'ciu_abreviatura',
        'ciu_codigo_postal', 'ciu_latitud', 'ciu_longitud', 'ciu_estado'
    ];
}
