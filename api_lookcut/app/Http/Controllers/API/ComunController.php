<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use App\Models\Establecimiento;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Responsables\registro_barberias\BarberiaUpdate;
use Illuminate\Support\Facades\DB;

class ComunController extends Controller
{
    use ApiResponser;

    public function cargarCiudades()
    {
        try {

            $array_ciudades = [
                7978,
                7822,
                7632,
                7604,
                7442,
                7706,
                5121,
                7861,
                7667,
                7476,
                7743,
                7124,
                1049,
                7213,
                2195,
                6374,
                2036,
                2703
            ];
            
            $ciudades = Ciudad::where('ciu_estado', 1)
                                ->whereNull('deleted_at')
                                ->whereIn('id_ciudad', $array_ciudades)
                                ->orderBy('ciu_descripcion', 'ASC')
                                ->get();

            $datos_ciudades = $this->construirJsonCiudades($ciudades);

            return $this->successResponseCiudades($datos_ciudades, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }

    private function construirJsonCiudades($ciudades)
    {
        $array_ciudad = array();

        foreach ($ciudades as $item) 
        {
            $ciudad = [ 
                "id_ciudad" => $item->id_ciudad,
                "id_departamento" => $item->id_departamento,
                "codigo_dane" => $item->ciu_codigo_dane,
                "ciu_descripcion" => $item->ciu_descripcion,
                "ciu_abreviatura" => $item->ciu_abreviatura,
                "codigo_postal" => $item->ciu_codigo_postal,
                "latitud" => $item->ciu_latitud,
                "longitud" => $item->ciu_longitud,
                "ciu_estado" => $item->ciu_estado
            ];

            array_push($array_ciudad, $ciudad);
        }
        return $array_ciudad;
    }

    public function cargarBarberias(Request $request)
    {
        $datos = $request->json();
        
        try {
        
            if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
            {
                $dato_usuario= $request->all();
                
                $barberias = DB::table('establecimiento')
                                ->join('ciudades', 'ciudades.id_ciudad', '=', 'establecimiento.ciudad_id')
                                ->join('tipo_negocio', 'tipo_negocio.id', '=', 'establecimiento.tipo_negocio_id')
                                ->select(
                                            'establecimiento.id', 
                                            'establecimiento.descripcion AS desc_establecimiento', 
                                            'establecimiento.nit', 
                                            'establecimiento.direccion', 
                                            'establecimiento.telefono', 
                                            'establecimiento.correo', 
                                            'establecimiento.latitud', 
                                            'establecimiento.longitud', 
                                            'establecimiento.tipo_negocio_id', 
                                            'ciudades.id_ciudad', 
                                            'ciudades.ciu_descripcion', 
                                            'ciudades.ciu_abreviatura', 
                                            'ciudades.ciu_latitud', 
                                            'ciudades.ciu_longitud', 
                                            'tipo_negocio.descripcion'
                                        )
                                ->where('usuario_id', $dato_usuario['usuario_id'])
                                ->where('establecimiento.tipo_negocio_id', 1)
                                ->whereNull('establecimiento.deleted_at')
                                ->whereNull('tipo_negocio.deleted_at')
                                ->whereNull('ciudades.deleted_at')
                                ->where('ciu_estado', 1)
                                ->orderBy('establecimiento.descripcion', 'ASC')
                                ->get();
    
            } else {
                return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
            }
            
            $datos_barberias = $this->construirJsonBarberias($barberias);
            return $this->successResponseBarberias($datos_barberias, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }

    private function construirJsonBarberias($barberias)
    {
        $array_barberia = array();

        foreach ($barberias as $item) 
        {
            $barberia = [ 
                "id" => $item->id,
                "descripcion" => $item->desc_establecimiento,
                "nit" => $item->nit,
                "des_ciudad" => $item->ciu_descripcion,
                "id_ciudad" => $item->id_ciudad,
                "direccion" => $item->direccion,
                "telefono" => $item->telefono,
                "correo" => $item->correo,
                "tipo_negocio_id" => $item->tipo_negocio_id,
                "desc_tipo_negocio" => $item->descripcion
           ];

            array_push($array_barberia, $barberia);
        }
        return $array_barberia;
    }

    public function cargarBarberiaPorId(Request $request)
    {
        try {
            
            $barberias = DB::table('establecimiento')
                                ->join('ciudades', 'ciudades.id_ciudad', '=', 'establecimiento.ciudad_id')
                                ->join('tipo_negocio', 'tipo_negocio.id', '=', 'establecimiento.tipo_negocio_id')
                                ->select('establecimiento.id', 'establecimiento.descripcion AS desc_establecimiento', 'establecimiento.nit', 'establecimiento.direccion', 'establecimiento.telefono', 'establecimiento.correo', 'establecimiento.latitud', 'establecimiento.longitud', 'establecimiento.tipo_negocio_id', 'ciudades.id_ciudad', 'ciudades.ciu_descripcion', 'ciudades.ciu_abreviatura', 'ciudades.ciu_latitud', 'ciudades.ciu_longitud', 'tipo_negocio.descripcion')
                                ->where('establecimiento.tipo_negocio_id', 1)
                                ->whereNull('establecimiento.deleted_at')
                                ->whereNull('tipo_negocio.deleted_at')
                                ->whereNull('ciudades.deleted_at')
                                ->where('ciu_estado', 1)
                                ->where('establecimiento.id', $request->id)
                                ->orderBy('establecimiento.descripcion', 'ASC')
                                ->get();

            $datos_barberias = $this->construirJsonBarberias($barberias);

            return $this->successResponseBarberias($datos_barberias, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }
    
    public function cargarDatosUsuarioPorId (Request $request)
    {
        try {
            
            $datosUsuario = DB::table('usuarios')
                            ->join('tipo_identificacion', 'tipo_identificacion.id', '=', 'usuarios.tipo_identificacion_id')
                            ->join('roles', 'roles.id', '=', 'usuarios.rol_id')
                            ->select(
                                        'usuarios.id_usuario',
                                        'usuarios.usuario',
                                        'usuarios.nombres',
                                        'usuarios.apellidos',
                                        'usuarios.estado', 
                                        'usuarios.fecha_nacimiento',
                                        'usuarios.lugar_nacimiento',
                                        'usuarios.cedula',
                                        'usuarios.grupo_sanguineo',
                                        'usuarios.telefono',
                                        'usuarios.celular',
                                        'usuarios.email',
                                        'usuarios.direccion',
                                        'usuarios.genero',
                                        'usuarios.tipo_identificacion_id',
                                        'usuarios.rol_id',
                                        'tipo_identificacion.descripcion AS tipo_doc',
                                        'roles.descripcion AS rol'
                                    )
                            ->where('usuarios.estado', 1)
                            ->whereNull('usuarios.deleted_at')
                            ->whereNull('tipo_identificacion.deleted_at')
                            ->whereNull('roles.deleted_at')
                            ->where('roles.estado', 1)
                            ->where('usuarios.id_usuario', $request->usuario_id)
                            ->get();

            $data_user = $this->construirJsonUsuario($datosUsuario);
            return $this->successResponseUsuario($data_user, 200);
            
        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }
    
    private function construirJsonUsuario($datosUsuario)
    {
        $array_usuario = array();

        foreach ($datosUsuario as $item) 
        {
            $usuario = [ 
                "usuario_id" => $item->id_usuario,
                "nombre_usuario" => $item->usuario,
                "nombres" => !is_null($item->nombres) ? $item->nombres : '',
                "apellidos" => !is_null($item->apellidos) ? $item->apellidos : '',
                "fecha_nacimiento" => !is_null($item->fecha_nacimiento) ? $item->fecha_nacimiento : '',
                "lugar_nacimiento" => !is_null($item->lugar_nacimiento) ? $item->lugar_nacimiento : '',
                "numero_documento" => !is_null($item->cedula) ? $item->cedula : '',
                "grupo_sanguineo" => !is_null($item->grupo_sanguineo) ? $item->grupo_sanguineo : '',
                "telefono" => !is_null($item->telefono) ? $item->telefono : '',
                "celular" => !is_null($item->celular) ? $item->celular : '',
                "correo" => !is_null($item->email) ? $item->email : '',
                "direccion" => !is_null($item->direccion) ? $item->direccion : '',
                "genero" => !is_null($item->genero) ? $item->genero : '',
                "tipo_documento" => !is_null($item->tipo_doc) ? $item->tipo_doc : '',
                "rol" => !is_null($item->rol) ? $item->rol : ''
           ];

            array_push($array_usuario, $usuario);
        }
        return $array_usuario;
    }
    
    public function eliminarBarberia(Request $request)
    {
        $datos = $request->json();

        if(is_object($datos) && !empty($datos) && !is_null($datos) && count($datos) > 0)
        {
            $barberUpdate = new BarberiaUpdate();
            return $barberUpdate->eliminarBarberia($request);
        } else {
            return $this->errorResponse(['respuesta' => 'Datos Invalidos o Vacios'], 401);
        } 
    }
    
    public function cargarCiudadesCodigoPostal()
    {
        try {
            
            $ciudadesCodigoPostal = Ciudad::where('ciu_estado', 1)
                                            ->whereNull('deleted_at')
                                            ->where('ciu_codigo_postal', '!=', '')
                                            ->orWhereNotNull('ciu_codigo_postal')
                                            ->orderBy('ciu_descripcion', 'ASC')
                                            ->get();

            $datos_ciudades = $this->construirJsonCiudades($ciudadesCodigoPostal);

            return $this->successResponseCiudades($datos_ciudades, 200);

        } catch (Exception $e) {
            return $this->errorResponse(['respuesta' => 'Ha ocurrido un error ' . $e->getMessage()], 400);
        }
    }
}
