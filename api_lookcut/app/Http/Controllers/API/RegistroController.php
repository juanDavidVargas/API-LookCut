<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responsables\registro_barberias\RegistroStore;
use App\Http\Responsables\registro_barberias\BarberiaUpdate;
use App\Http\Responsables\registro_usuarios\RegistroUsuarioStore;
use App\Models\Roles;
use App\Models\TipoIdentificacion;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class RegistroController extends Controller
{
    use ApiResponser;

    public function registroBarberias(Request $request)
    {
        $datos = $request->json();

        if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
        {
            return new RegistroStore();

        } else {
            return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
        }
    }

    public function tiposDocumento(Request $request)
    {
        try {
            
            $documentos = TipoIdentificacion::whereNull('deleted_at')
                                ->orderBy('descripcion', 'ASC')
                                ->get();

            $datos_documento = $this->construirJsonTiposIdentificacion($documentos);

            return $this->successResponseTiposDocumento($datos_documento, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }

    public function roles(Request $request)
    {
        try {
            
            $roles = Roles::whereNull('deleted_at')
                                ->whereNotIn('id', array(4))
                                ->where('estado', 1)
                                ->orderBy('descripcion', 'ASC')
                                ->get();

            $datos_roles = $this->construirJsonRoles($roles);

            return $this->successResponseRoles($datos_roles, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }

    private function construirJsonTiposIdentificacion($documentos)
    {
        $array_documento = array();

        foreach ($documentos as $item) 
        {
            $tipo_documento = [ 
                "id" => $item->id,
                "descripcion" => $item->descripcion
            ];

            array_push($array_documento, $tipo_documento);
        }
        return $array_documento;
    }

    private function construirJsonRoles($roles)
    {
        $array_roles = array();

        foreach ($roles as $item) 
        {
            $roles = [ 
                "id" => $item->id,
                "descripcion" => $item->descripcion
            ];

            array_push($array_roles, $roles);
        }
        return $array_roles;
    }

    public function registroUsuarios(Request $request)
    {
        $datos = $request->json();

        if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
        {
            return new RegistroUsuarioStore();

        } else {
            return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
        }
    }

    public function editarBarberia(Request $request)
    {
        $datos = $request->json();

        if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
        {
            return new BarberiaUpdate();

        } else {
            return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
        }
    }
}
