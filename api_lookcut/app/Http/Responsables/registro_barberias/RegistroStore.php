<?php

namespace App\Http\Responsables\registro_barberias;

use App\Models\Establecimiento;
use App\Traits\ApiResponser;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

/**
 * Recibe los datos para verificar el usuario para el login
 */
class RegistroStore implements Responsable
{
    use ApiResponser;

    public function toResponse($request)
    {
        DB::connection('mysql')->beginTransaction();
        $datos_registro = $request->all();

        $nombre = $datos_registro['nombre'];
        $documento = $datos_registro['documento'];
        $ciudad = $datos_registro['ciudad'];
        $direccion = $datos_registro['direccion'];
        $telefono = $datos_registro['telefono'];
        $email = $datos_registro['correo_electronico'];
        $es_barberia = $datos_registro['es_barberia'];

        if((isset($nombre) && empty($nombre) || is_null($nombre)) || 
           (isset($documento) && empty($documento) || is_null($documento)) ||
           (isset($ciudad) && empty($ciudad) || is_null($ciudad)) || 
           (isset($es_barberia) && empty($es_barberia) || is_null($es_barberia)))
        {
            return $this->errorResponse([
                'message' => 
                        "Los siguientes campos son obligatorios: Nombre, Nit y Ciudad, verifique e intente de nuevo."
            ], 404);
            exit;
        } else {

            if($es_barberia)
            {
                $tipo_negocio_id = 1;
            } else {
                return $this->errorResponse([
                    'message' => 
                        "Por favor indicar el tipo de negocio, si es Barberia o Sala de Belleza"
                ], 404);
                exit;
            }
                try {
                    
                    $barberia = Establecimiento::create([
                        'descripcion' => strtoupper($nombre),
                        'nit' => $documento,
                        'ciudad_id' => $ciudad,
                        'direccion' => $direccion,
                        'telefono' => $telefono, 
                        'correo' => $email,
                        'latitud' => null,
                        'longitud' => null,
                        'tipo_negocio_id' => $tipo_negocio_id
                    ]);
                    
                    if($barberia)
                    {
                        DB::connection('mysql')->commit();
                        return $this->successResponse([
                            'message' => 
                                    "Barberia Registrada Correctamente!"
                        ], 200);
    
                    } else {
                        return $this->errorResponse([
                            'message' => 
                                    "Ha ocurrido un error registrando la barberia, intente de nuevo, si el problema persiste contacte a Soporte!"
                        ], 404);
    
                        DB::connection('mysql')->rollback();
                    }

                } catch (Exception $e) {

                    return $this->errorResponse([
                        'message' => 
                                "Ha ocurrido un error de base de datos, intente de nuevo!"
                    ], 404);
                }
        }
    }

    private function contruirJsonDatosUsuario($usuario, $array_usuario)
    {
        $datos_usuario = array();
        $datos_usuario = [ 
            "id_usuario" => $usuario->id_usuario,
            "usuario" => $usuario->usuario,
            "nombres" => $usuario->nombres,
            "apellidos" => $usuario->apellidos,
            "estado" => $usuario->estado,
            "fecha_nacimiento" => $usuario->fecha_nacimiento,
            "lugar_nacimiento" => $usuario->lugar_nacimiento,
            "cedula" => $usuario->cedula,
            "grupo_sanguineo" => $usuario->grupo_sanguineo,
            "telefono" => $usuario->telefono,
            "celular" => $usuario->celular,
            "email" => $usuario->email,
            "direccion" => $usuario->direccion,
            "clave_fallas" => $usuario->clave_fallas,
            "fecha_ingreso" => $usuario->fecha_ingreso,
            "genero" => $usuario->genero,
            "tipo_identificacion_id" => $usuario->tipo_identificacion_id,
            "rol_id" => $usuario->rol_id
        ];

        array_push($array_usuario, $datos_usuario);
        return $array_usuario;
    }
}