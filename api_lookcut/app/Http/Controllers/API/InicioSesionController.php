<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Responsables\inicio_sesion\InicioSesionShow;
use Illuminate\Support\Facades\Session;
use Exception;

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

    public function logout(Request $request)
    {
        try {
           
            Session::forget('username');
            Session::forget('sesion_iniciada');
            Session::forget('usuario_id');
            Session::flush();
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return $this->successResponse([
                'message' => 
                        "La sesión ha sido cerrada correctamente" 
            ], 200);

        } catch (Exception $e) 
        {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error inesperado"],
                         400);
        }
    }

    public function validarSesion(Request $request)
    {
        try {

            $username = request('username', null);
            $sesion_iniciada = request('sesion_iniciada', null);
            $usuario_id = request('id_usuario', null);

            if((is_null($username) || empty($username)) &&
               (is_null($sesion_iniciada) || empty($sesion_iniciada)) && 
               (is_null($usuario_id) || empty($usuario_id)))
            {
                return $this->errorResponse([
                    'message' => 
                            "Actualmente no tienen una sesión válida, por favor inicie sesión para acceder a esta opción"],
                            400);
            }
    
        } catch (Exception $e) 
        {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error inesperado"],
                         400);
        }
    }
}
