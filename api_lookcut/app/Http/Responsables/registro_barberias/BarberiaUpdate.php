<?php

namespace App\Http\Responsables\registro_barberias;

use App\Models\Establecimiento;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarberiaUpdate implements Responsable
{
    use ApiResponser;

    public function toResponse($request)
    {
        DB::connection('mysql')->beginTransaction();
        $datos = $request->all();

        $id = $datos['id'];
        $nombre = $datos['nombre'];
        $documento = $datos['nit'];
        $ciudad = $datos['ciudad_id'];
        $direccion = $datos['direccion'];
        $telefono = $datos['telefono'];
        $email = $datos['correo'];
        $es_barberia = $datos['es_barberia'];
        $usuario_id = $datos['usuario_id'];

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

                    $barberia = Establecimiento::find($id)->first();

                    if(isset($barberia) && !empty($barberia) && 
                       !is_null($barberia))
                    {
                        $barberia->descripcion = strtoupper($nombre);
                        $barberia->nit = $documento;
                        $barberia->ciudad_id = intval($ciudad);
                        $barberia->direccion = $direccion;
                        $barberia->telefono = $telefono;
                        $barberia->correo = $email;
                        $barberia->tipo_negocio_id = $tipo_negocio_id;
                        $barberia->usuario_id = $usuario_id;
                        $barberia->save();

                        if($barberia)
                        {
                            DB::connection('mysql')->commit();
                            return $this->successResponse([
                                'message' => 
                                        "Barberia actualizada Correctamente!"
                            ], 200);
                        } else {

                            return $this->errorResponse([
                                'message' => 
                                        "Ha ocurrido un error actualizando la barberia, intente de nuevo, si el problema persiste contacte a Soporte!"
                            ], 404);
        
                            DB::connection('mysql')->rollback();
                        }

                    } else {
                        return $this->errorResponse([
                            'message' => 
                                    "No se encontraron datos de la barberia, intente de nuevo, si el problema persiste contacte a Soporte!"
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
    
    public function eliminarBarberia($request)
    {
        try 
        {
            $barberia = Establecimiento::where('id', $request->id)
                                        ->where('deleted_at', null)
                                        ->first();
            
            if(isset($barberia) && !empty($barberia) && 
               !is_null($barberia))
            {
                $barberia->deleted_at = Carbon::now()->format('y-m-d H:i:s');
                $barberia->save();
                
                if($barberia)
                {
                    return $this->successResponse([
                        'message' => 
                                "Barberia eliminada Correctamente!"
                    ], 200);
                } else {

                    return $this->errorResponse([
                        'message' => 
                                "Ha ocurrido un error eliminando la barberia, intente de nuevo, si el problema persiste contacte a Soporte!"
                    ], 404);
                }
            } else {
                return $this->errorResponse([
                    'message' => 
                            "No se encontraron datos con la informacion proporcionada"
                ], 404);
            }
            
        } catch (Exception $e) 
        {
            return $this->errorResponse([
                'message' => 
                        "Ha ocurrido un error de base de datos, intente de nuevo!"
            ], 404);
        }
    }
}