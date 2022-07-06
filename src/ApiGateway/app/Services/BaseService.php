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
    protected $secret;

    /**
     * @var array
     */
    protected $headers;

    public function __construct(array $options = [])
    {
        $headers = $options['headers'];
        $data = $options['data'];
        $hostFile = storage_path() . '/hosts.json';
        $config = json_decode(file_get_contents($hostFile), true);

        // Init service for host
        $host = collect($config)->filter(function($value, $key) use($options) {
            $value['host'] = $options['host'];
        });

        foreach ($host as $config => $value) {
            $this->baseUri = $key['']
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
