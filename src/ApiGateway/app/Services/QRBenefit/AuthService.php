<?php

declare(strict_types = 1);

namespace App\Services\QRBenefit;

use App\Traits\RequestService;

use function config;

class AuthService
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

    public function __construct()
    {
        $this->baseUri = 'http://nginx:2001';
        $this->secret = '';
        $this->headers = [
            'Authorization' => app('request')->header('Authorization-Service')
        ];
    }

    /**
     * @return string
     */
    public function login(array $data) : string
    {
        return $this->request('POST', '/api/store/login', $data);
    }

    /**
     * @return string
     */
    public function info() : string
    {
        return $this->request('GET', '/api/store/info');
    }
}
