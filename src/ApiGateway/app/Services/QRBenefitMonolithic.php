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
        $hostFile = storage_path() . '/host.json';
        $config = json_decode(file_get_contents($hostFile), true);
        foreach ($config as $key => $value) {

        }
    }

    /**
     * @return string
     */
    public function post(array $data) : string
    {
        $headers = array_merge($this->headers, config('json.headers'));
        return $this->get($data['url'], $headers);
    }
}
