<?php

declare(strict_types = 1);

namespace App\Services;

use function config;

use App\Traits\RequestService;
use Illuminate\Http\Request;

class BaseService
{
    use RequestService;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $destination;

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
    }

    public function customHeaderKeys(array $headers = array(), array $headerKeys = [])
    {
        foreach ($headerKeys as $key => $customKey) {
            // $this->headers[$key] = $headers[$customKey];
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setBaseURI($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @return string
     */
    public function load($method, $url, array $data) : string
    {
        $data = [
            'form_params' => $data
        ];

        return $this->request($method, $url, $data);
    }
}
