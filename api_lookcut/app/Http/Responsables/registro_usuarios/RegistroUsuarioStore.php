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
        $tipo_documento = $datos_usuario['tipo_documento'];
        $documento = $datos_usuario['documento'];
        $correo = $datos_usuario['correo'];
        $rol = $datos_usuario['rol'];

        $consulta_cedula = $this->consultarCedula($documento);

        if($consulta_cedula == "exception_consulta_cedula")
        {
            DB::connection('mysql')->rollback();
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error consultando la cédula, ìntente de nuevo."
            ], 400);
            exit;
        }

        if(isset($consulta_cedula) && !empty($consulta_cedula) && !is_null($consulta_cedula))
        {
            DB::connection('mysql')->rollback();
            return $this->errorResponse([
                'message' => 
                        "Ya existe un registro en la base de datos con el mismo número de documento ingresado, por favor cámbielo."
            ], 400);
            exit;
        }

         // Contruimos el nombre de usuario
         $separar_apellidos = explode(" ", $apellidos);
         $usuario = substr($this->quitarCaracteresEspeciales(trim($nombres)), 0,1) . trim($this->quitarCaracteresEspeciales($separar_apellidos[0]));
         $usuario = preg_replace("/(Ñ|ñ)/", "n", $usuario);
         $usuario = strtolower($usuario);
         $complemento = "";

         while($this->consultaUsuario($usuario.$complemento))
         {
             $complemento++;
         }

        if(isset($nombres) && empty($nombres) || 
           isset($apellidos) && empty($apellidos) ||
           isset($tipo_documento) && empty($tipo_documento) ||
           isset($documento) && empty($documento) ||
           isset($rol) && empty($rol))
        {
            DB::connection('mysql')->rollback();
            return $this->errorResponse([
                'message' => 
                        "Todos los campos son obligatorios, verifique e intente de nuevo."
            ], 400);
            exit;
        } else {

            try {
                
                $usuario = User::create([
                    'usuario' => $usuario.$complemento,
                    'password' => Hash::make($documento),
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
                                "Usuario Creado Correctamente, el nombre de usuario es: " .$usuario->usuario . " y la contraseña es su número de cédula!" 
                    ], 200);

                } else {

                    DB::connection('mysql')->rollback();
                    return $this->errorResponse([
                        'message' => 
                                "Ha ocurrido un error creando el usuario, intente de nuevo, si el problema persiste contacte a Soporte!"
                    ], 404);
                }

            } catch (Exception $e) {
                DB::connection('mysql')->rollback();
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

    private function consultarCedula($numero_documento)
    {
        try {

            $cedula = User::where('cedula', $numero_documento)
                            ->get()
                            ->first();

            if(isset($cedula) && !empty($cedula) && !is_null($cedula))
            {
                return $cedula;
            } else {
                return null;
            }

        } catch (Exception $e)
        {
            return "exception_consulta_cedula";
        }
    }

    private function consultaUsuario($usuario)
    {
        try {

            $usuario = User::where('usuario', $usuario)
                            ->get()
                            ->first();
            return $usuario;

        } catch (Exception $e) {
           return "exception_usuario";
        }
    }

    private function quitarCaracteresEspeciales($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ",
                               "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”",
                               "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹", "ñ", "Ñ", "*");

        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U",
                            "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U",
                            "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "n", "N", "");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }
}