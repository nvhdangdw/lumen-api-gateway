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
    protected $host;

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
     * @param $path string
     * @param $method string
     * @param $headers array
     *
     * @return void
     */
    public function updateConfig($host, $path, $method, $headers)
    {
        $this->host = collect($this->hostConfig)->filter(function($value, $hostKey) use ($host) {
            return $host === $hostKey;
        })->first();

        $this->api = collect($this->apiConfig)->filter(function($item) use($host, $path, $method) {
            return
                $host === $item['host'] &&
                is_numeric(strpos($item['url'], $path)) &&
                $method === $item['method'];
        })->first();

        $this->baseUri = $this->host['base_uri'];

        // Update custom header keys
        if (isset($this->host['headers'])) {
            foreach ($this->host['headers'] as $key => $value) {
                $customKey = strtolower($value);
                if (isset($headers[$customKey])) {
                    $this->headers[$key] = $headers[$customKey];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function validator()
    {
        return $this->api && $this->host;
    }

    /**
     * @return string
     */
    public function load(array $data) : string
    {
        $data = [
            'form_params' => $data
        ];

        return $this->request($this->api['method'], $this->api['destination'], $data);
    }
}
