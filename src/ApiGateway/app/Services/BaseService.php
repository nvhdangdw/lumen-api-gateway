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
    protected $method;

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

    /**
     * @var array
     */
    protected $hostConfig;

    /**
     * @var array
     */
    protected $apiConfig;

    /**
     * @var array
     */
    protected $error = [];

    /**
     * @var array
     */
    protected $api = [];

    public function __construct()
    {
        $this->hostConfig = json_decode(file_get_contents(storage_path() . '/hosts.json'), true);
        $this->apiConfig = json_decode(file_get_contents(storage_path() . '/api_config.json'), true)['api_config'];
    }

    /**
     *
     *
     * @param $host string
     * @param $url string
     * @param $method string
     * @param $headers array
     *
     * @return void
     */
    public function updateConfig($host, $url, $method, $headers)
    {
        $config = collect($this->hostConfig)->filter(function($value, $hostKey) use ($host) {
            return $host === $hostKey;
        })->first();

        $this->hostConfig = $config;
        $this->baseUri = $config['base_uri'];
        $this->host = $host;
        $this->method = $method;
        $this->url = '/' . $host . '/' . $url;
        $self = $this;

        // Get api
        $this->api = collect($this->apiConfig)->filter(function($item) use($self) {
            return
                $self->url === $item['url'] &&
                $self->method === $item['method'];
        })->first();

        // Update custom header keys
        if (isset($config['headers'])) {
            foreach ($config['headers'] as $key => $cusstomKey) {
                if (isset($headers[$cusstomKey])) {
                    $this->headers[$key] = $headers[$cusstomKey];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function validator()
    {
        return $this->api;
    }

    /**
     * @return string
     */
    public function load(array $data) : string
    {
        $data = [
            'form_params' => $data
        ];

        return $this->request($this->method, $this->api['destination'], $data);
    }
}
