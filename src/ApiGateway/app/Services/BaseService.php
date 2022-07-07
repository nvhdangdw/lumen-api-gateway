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
    protected $secret;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $api = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $hostConfig;

    /**
     * @var array
     */
    protected $error = [];


    public function __construct()
    {
        $this->config = [
            'host_config' => json_decode(file_get_contents(storage_path() . '/hosts.json'), true),
            'api_config' => json_decode(file_get_contents(storage_path() . '/api_config.json'), true)['api_config'],
        ];
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
        $hostConfig = collect($this->config['host_config'])->filter(function($value, $hostKey) use ($host) {
            return $host === $hostKey;
        })->first();

        $this->hostConfig = $hostConfig;
        $this->host = $host;
        $this->method = $method;
        $this->url = '/' . $host . '/' . $url;
        $self = $this;

        // Get api
        $this->api = collect($this->config['api_config'])->filter(function($item) use($self) {
            return
                $self->url === $item['url'] &&
                $self->method === $item['method'];
        })->first();

        // Update custom header keys
        if (isset($hostConfig['headers'])) {
            foreach ($hostConfig['headers'] as $key => $cusstomKey) {
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
