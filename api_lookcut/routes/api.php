<?php

header('Content-type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, username, password, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

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
    Route::post('app/ciudades', 'ComunController@cargarCiudades');

    //Registro Usuarios
    Route::post('app/tipos_documento', 'RegistroController@tiposDocumento');
    Route::post('app/roles', 'RegistroController@roles');
    Route::post('app/registro', 'RegistroController@registroUsuarios');

    //Registro Barberias
    Route::post('app/registro_barberias', 'RegistroController@registroBarberias');
});
