<?php

namespace App\Http\Responsables\registro_usuarios;

use App\Traits\ApiResponser;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Recibe los datos para verificar el usuario para el login
 */
class RegistroUsuarioStore implements Responsable
{
    use ApiResponser;

    public function toResponse($request)
    {
        DB::connection('mysql')->beginTransaction();
        $datos_usuario = $request->all();

        $nombres = $datos_usuario['nombres'];
        $apellidos = $datos_usuario['apellidos'];
        $usuario = $datos_usuario['usuario'];
        $tipo_documento = $datos_usuario['tipo_documento'];
        $documento = $datos_usuario['documento'];
        $correo = $datos_usuario['correo'];
        $contrasenia = $datos_usuario['contrasenia'];
        $rol = $datos_usuario['rol'];

        if(isset($nombres) && empty($nombres) || 
           isset($apellidos) && empty($apellidos) ||
           isset($usuario) && empty($usuario) ||
           isset($tipo_documento) && empty($tipo_documento) ||
           isset($documento) && empty($documento) ||
           isset($contrasenia) && empty($contrasenia) ||
           isset($rol) && empty($rol))
        {
            return $this->errorResponse([
                'message' => 
                        "Todos los campos son obligatorios, verifique e intente de nuevo."
            ], 400);
            exit;
        } else {

            try {
                
                $usuario = User::create([
                    'usuario' => $usuario,
                    'password' => Hash::make($contrasenia),
                    'nombres' => strtoupper($nombres),
                    'apellidos' => strtoupper($apellidos),
                    'estado' => 1, 
                    'cedula' => $documento,
                    'email' => strtolower($correo),
                    'clave_fallas' => 0,
                    'fecha_ingreso' => Carbon::now()->timestamp,
                    'tipo_identificacion_id' => $tipo_documento,
                    'rol_id' => $rol
                ]);
                
                if($usuario)
                {
                    DB::connection('mysql')->commit();
                    return $this->successResponse([
                        'message' => 
                                "Usuario Creado Correctamente!"
                    ], 200);

                } else {
                    return $this->errorResponse([
                        'message' => 
                                "Ha ocurrido un error creando el usuario, intente de nuevo, si el problema persiste contacte a Soporte!"
                    ], 404);

                    DB::connection('mysql')->rollback();
                }

            } catch (Exception $e) {
                return $this->errorResponse([
                    'message' => 
                            "Ha ocurrido un error de base de datos, íntente de nuevo!"
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