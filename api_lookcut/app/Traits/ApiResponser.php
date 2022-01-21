<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    /**
     * Undocumented function
     *
     * @param [type] $data
     * @param integer $code
     * @return JsonResponse
     */
    public function successResponse($data, $code = 200)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(['User'=>$data, 'code' => $code], $code, $headers);
    }

    public function successResponseMenu($data, $code = 200)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(["Menu" => $data], $code, $headers);
    }

    public function successResponseSubmenu($data, $code = 200)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(["Submenu" => $data], $code, $headers);
    }

    public function successResponseRegistro($message, $code = 201){
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(['exito' => $message, 'code' => $code], $code, $headers);
    }

    public function successUpdateProfile($message, $code = 200)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(['success' => $message, 'code' => $code], $code, $headers);
    }

    public function successPostGeolocalizacion($message, $code = 200)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(['success' => $message, 'code' => $code], $code, $headers);
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @param integer $code
     * @return JsonResponse|void
     */
    public function errorResponse($message, $code = 400)
    {
        $headers = ['Content-Type'=>'application/json; charset=utf-8'];
        return response()->json(['error' => $message, 'code' => $code], $code, $headers);
    }

    /**
     * Undocumented function
     *
     * @param Collection $collection
     * @param integer $code
     * @return JsonResponse
     */
    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);

        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @param integer $code
     * @return JsonResponse
     */
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    /**
     * Undocumented function
     *
     * @param $data
     * @param $transformer
     * @return array
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    /**
     * Guarda en cache los datos de respuesta de una consulta dependiendo de la url
     *
     * @param Model $data puede variar dependiendo de la consulta
     * @param integer $minutes el tiempo en minutos que se va a guardar en la cache
     * @return void $data puede variar dependiendo de la consulta
     */
    public function cacheResponse($data = null, $minutes = 60)
    {
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        return $this->cacheData($fullUrl, $data, $minutes);
    }

    /**
     *
     *
     * @param [type] $name
     * @param [type] $data
     * @param integer $minutes
     * @return void
     */
    public function cacheData($name, $data = null, $minutes = 900)
    {
        if (Cache::has($name)) {
            return Cache::get($name);
        }else{
            return Cache::remember($name, $minutes, function() use($data) {
                return $data;
            });
        }
    }
}

