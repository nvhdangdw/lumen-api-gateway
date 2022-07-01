<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        return $this->addAccessControlHeaders($request, $next($request));
    }

    /**
     * Add Access Control headers.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Http\Response $response
     *
     * @return \Illuminate\Http\Response
     */
    public function addAccessControlHeaders($request, $response)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => $this->getHTTPOrigin(),
            'Access-Control-Allow-Methods'     => 'PUT,POST,GET,DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => 'origin, content-type, accept, authorization, tfa',
        ];

        foreach ($headers as $header => $value) {
            $response->header($header, $value);
        }

        return $response;
    }

    private function getHTTPOrigin()
    {
        try {
            $origin = request()->header('origin');
            $url = parse_url($origin);
            $f = false;
            foreach (config('cors.origins') as $domain) {
                if (is_numeric(strpos($url['host'], $domain))) {
                    $f = true;
                    break;
                }
            }
            if ($f) {
                return $origin;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
