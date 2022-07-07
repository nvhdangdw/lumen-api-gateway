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

    }

    /**
     * @return mixed
     */
    public function request(Request $request, $host, $url)
    {
        $this->service->updateConfig($host, $url, $request->getMethod(), $request->header());
        return $this->load($request->all());
    }

    /**
     * @return mixed
     */
    private function load($data)
    {
        $validator = $this->service->validator();

        if (isset($validator)) {
            return $this->successResponse($this->service->load($data));
        }
        return $this->errorResponse('Method not allowed!',  400);
    }
}
