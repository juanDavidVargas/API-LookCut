<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class ComunController extends Controller
{
    use ApiResponser;

    public function cargarCiudades()
    {
        try {
            
            $ciudades = Ciudad::where('ciu_estado', 1)
                                ->whereNull('deleted_at')
                                ->whereNotNull('ciu_abreviatura')
                                ->where('ciu_codigo_dane', 1)
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
}
