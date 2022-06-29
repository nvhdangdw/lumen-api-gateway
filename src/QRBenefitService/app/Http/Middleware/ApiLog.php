<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\APIActivity;

class ApiLog
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
        $response = $next($request);
        $user = Auth::user();
        $messages = array(
            '401' => 'You are not authorized to access this API.',
            '403' => 'You do not have permission to access this API.',
            '404' => 'Sorry, the API you are looking for could not be found.',
            '405' => 'The method is not allowed to access this API.',
            '5xx' => 'The service is temporarily unreachable',
            'default' => $response->getContent()
        );
        $statusCode = $response->getStatusCode() ?? 500;
        $message = $messages['default'];

        if ($statusCode == 200 && !is_null(json_decode($message)) && !json_decode($message)->status) {
            $statusCode = 400;
        }

        if ($statusCode >= 401 && $statusCode <= 405) {
            $message = $messages[$statusCode];
        } else if ($statusCode >= 500 && $statusCode < 600) {
            $message = $messages['5xx'];
        }

        $record = array(
            'route' => $request->path(),
            'method' => $request->method(),
            'user_id' => $user->id ?? 0,
            'payload' =>  $request->getContent(),
            'status_code' => $statusCode,
            'response' => $message,
        );

        APIActivity::create($record);

        return $response;
    }
}
