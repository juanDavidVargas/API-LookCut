<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api', 'throttle:60,1')->group(function () {
    // Endpoint pruebas
    Route::post('app/index', 'InicioSesionController@index');
    
    // Inicio Sesion
    Route::post('app/inicio_sesion', 'InicioSesionController@inicioSesion');
});
