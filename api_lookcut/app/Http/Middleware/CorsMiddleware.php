<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Content-Type'                     => 'application/json; charset=UTF-8',
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Headers'     => 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, Access-Control-Allow-Origin',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Allow'                            => 'GET, POST, OPTIONS, PUT, DELETE',
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }
        return $response;
    }
}
