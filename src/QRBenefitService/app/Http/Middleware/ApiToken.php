<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\ResponseData;
use App\Setting;

class ApiToken
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
        $apiKey = $request->header('x-api-key');

        if (!Setting::checkApiKey($apiKey)) {
            $this->responseData = new ResponseData();
            $errors = array(
                "x_api_key" => ["Unauthenticated"],
            );
            return response()->json($this->responseData->error($errors), 401);
        }

        return $next($request);
    }
}
