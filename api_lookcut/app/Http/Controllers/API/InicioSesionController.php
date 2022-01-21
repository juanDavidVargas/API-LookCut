<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Responsables\inicio_sesion\InicioSesionShow;

class InicioSesionController extends ApiController
{
    use ApiResponser;
    
    public function index(Request $request){
        $res = [
            "status" => "OK",
            "message" => "La API funciona correctamente"
        ];

        return $res;
    }

    public function inicioSesion(Request $request)
    {
        $datos = $request->json();

        if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
        {
            return new InicioSesionShow();

        } else {
            return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
        }
    }
}
