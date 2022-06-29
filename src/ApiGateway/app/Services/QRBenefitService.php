<?php

declare(strict_types = 1);

namespace App\Services;

use function config;
use function request;

use App\Traits\RequestService;
use Illuminate\Http\Request;

class QRBenefitService
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
        $this->baseUri = env('QR_BENEFIT_SERVICE_BASE_URI', '');
        $this->secret = env('QR_BENEFIT_SERVICE_SECRET', '');
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
