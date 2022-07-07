<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @var \App\Services\BaseService
     */
    protected $service;

    /**
     * @var array
     */
    protected $config;

    /**
     * Constructor.
     *
     */
    public function __construct(BaseService $service)
    {
        $this->service = new $service;
        $this->config = json_decode(file_get_contents(storage_path() . '/hosts.json'), true);
    }

    /**
     * @return mixed
     */
    public function request(Request $request, $host, $url)
    {
        $config = collect($this->config)->filter(function($value, $hostKey) use ($host) {
            return $host === $hostKey;
        })->first();

        $this->service->setHost($host);
        $this->service->setBaseURI($config['base_uri']);
        $this->service->customHeaderKeys($request->header(), (array) $config['headers']);

        switch ($request->getMethod()) {
            case 'GET':
                return $this->load('GET', $url, $request->all());
                break;

            case 'POST':
                return $this->load('POST', $url, $request->all());
                break;

            case 'PUT':
                return $this->load('PUT', $url, $request->all());
                break;

            case 'DELETE':
                return $this->load('DELETE', $url, $request->all());
                break;

            default:
                return $this->errorResponse('Method not allowed!',  400);
                break;
        }
    }

    /**
     * @return mixed
     */
    private function load($method, $url, $data)
    {
        return $this->successResponse($this->service->load($method, $url, $data));
    }
}
