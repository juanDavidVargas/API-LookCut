<?php

namespace App\Http\Responsables\inicio_sesion;

use App\Traits\ApiResponser;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Hash;

/**
 * Recibe los datos para verificar el usuario para el login
 */
class InicioSesionShow implements Responsable
{
    use ApiResponser;

    public function toResponse($request)
    {
        $datos_sesion = $request->all();
        $user = $datos_sesion['username'];
        $clave = $datos_sesion['password'];
        $array_usuario = [];

        if(isset($user) && empty($user) && 
           isset($password) && empty($password))
        {
            return $this->errorResponse([
                'message' => 
                        "El usuario y contraseña son obligatorios"
            ], 404);
            exit;
        }

        $usuario = $this->datosUsuario($user);

        if(isset($usuario) && !empty($usuario))
        {
            $cont_clave_erronea = $usuario->clave_fallas;

            if($usuario->clave_fallas >= 5)
            {
                $this->inactivarUsuario($usuario->id_usuario);
            }

            if($usuario->estado == 0 || $usuario->estado == false || 
               $usuario->estado == "false")
            {
                return $this->errorResponse([
                    'message' => 
                            "El usuario {$user} se encuentra bloqueado, contácte a soporte para desbloquearlo."
                ], 403);
                exit;
            }

            if(Hash::check($clave, $usuario->password))
            {
                // Creamos las variables de sesion
                session()->put('usuario_id', $usuario->id_usuario);
                session()->put('username', $usuario->usuario);
                session()->put('sesion_iniciada', true);

                $datos_usuario = $this->contruirJsonDatosUsuario($usuario, $array_usuario);

                // Metemos las variables de sesion al array de datos_usuario
                array_push($datos_usuario, session('username'));
                array_push($datos_usuario, session('sesion_iniciada'));
                array_push($datos_usuario, session('usuario_id'));

                $this->actualizarClaveFallas($usuario->id_usuario, 0);

            } else 
            {
                $cont_clave_erronea += 1;
                $this->actualizarClaveFallas($usuario->id_usuario, $cont_clave_erronea);
                return $this->errorResponse(['message' => "Credenciales invalidas."], 401);
            }

            // Retornamos objeto con los datos del usuario y las variables de sesion creadas
            return $this->successResponse($datos_usuario, 200);
        } else {
            return $this->errorResponse([
                'message' => 
                        "No se encontraron registros para el usuario {$user}"],
                         404);
        }
    }

    private function inactivarUsuario($usuario_id)
    {
        try {
            $user = User::find($usuario_id);
            $user->estado = 0;
            $user->save();

        } catch (Exception $e) {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error inesperado"],
                         400);
        }
    }

    private function actualizarClaveFallas($usuario_id, $contador)
    {
        try {
            $user = User::find($usuario_id);
            $user->clave_fallas = $contador;
            $user->save();

        } catch (Exception $e) {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error inesperado"],
                         400);
        }
    }

    private function datosUsuario($user)
    {
        try {
            return User::where('usuario', $user)
                        ->whereNull('deleted_at')
                        ->get()
                        ->first();

        } catch (Exception $e) {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error inesperado"],
                         400);
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