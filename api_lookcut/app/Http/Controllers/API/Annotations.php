<?php

/**
 * @OA\OpenApi(
 *  @OA\Info(
 *      description="",
 *      version="v1.1",
 *      title="API APP LOOKCUT",
 *      @OA\Contact(
 *           name="Support.",
 *           email="davidvargas.jdvp@gmail.com.co"
 *       )
 *  ),
 *  @OA\Server(
 *       description="Servidor de pruebas",
 *       url=L5_SWAGGER_CONST_HOST
 *  ),
 *  @OA\Server(
 *       description="Servidor de producción",
 *       url=L5_SWAGGER_CONST_HOST_P
 *  ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/app/login",
 *     security={{"passport": {}}},
 *     tags={"Inicio Sesion"},
 *     summary="Validar usuario y contraseña para el inicio de sesión en la APP",
 *     description="Ingresando un usuario y contraseña se valida que exista el registro en la base de datos.",
 *     operationId="show",
 *     @OA\Parameter(
 *         description="nombre de usuario.",
 *         in="path",
 *         name="username",
 *         required=true,
 *         @OA\Schema(
 *           type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         description="clave.",
 *         in="path",
 *         name="password",
 *         required=true,
 *         @OA\Schema(
 *           type="string"
 *         )
 *     ),
 *     @OA\RequestBody(
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Validación exitosa, se retorna JSON con información del usuario.",
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Error de Autenticación, credenciales inválidas."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Usuario bloqueado."
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Usuario no encontrado, no existe en la base de datos."
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Error inesperado."
 *     )
 * )
 */


