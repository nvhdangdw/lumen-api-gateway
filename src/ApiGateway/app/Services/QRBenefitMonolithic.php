<?php

declare(strict_types = 1);

namespace App\Services;

use function config;
use function request;

use App\Traits\RequestService;
use Illuminate\Http\Request;

class QRBenefitMonolithic
{
    use RequestService;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var array
     */
    protected $headers;

    public function __construct()
    {
        $this->baseUri = 'http://nginx:1000';
        $this->secret = '';
        $this->headers = [
            'Authorization' => app('request')->header('Authorization-Service')
        ];
    }

    /**
     * @return string
     */
    public function passwordForgot(array $data) : string
    {
        return $this->request('POST', '/api/store/password/forgot', $data);
    }

    /**
     * @return string
     */
    public function get(array $data) : string
    {
        $headers = array_merge($this->headers, config('json.headers'));
        return $this->get($data['path'], $headers);
    }
}
