<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $connection = 'mysql';
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'descripcion'
    ];
}
