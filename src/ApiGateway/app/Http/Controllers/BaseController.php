<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    /**
     * @var \App\Services\QRBenefitMonolithic
     */
    protected $service;

    /**
     * Constructor.
     *
     */
    public function __construct(Request $request, $host, $url)
    {
        $this->service = new BaseService([
            'host' => $host,
            'path' => $url,
            'data' => $request->all(),
            'headers' => $request->header(),
        ]);
    }

    /**
     * @return mixed
     */
    public function post(Request $request)
    {
        $data = $request->all();

        $path = isset($data['path']) ? $data['path'] : '';

        $validates = collect(config('json.validate'))->filter(function ($value, $key) use ($path) {
            return $value['path'] === $path;
        })->values('validates');

        $validator = Validator::make($data, $validates);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $response = $this->successResponse($this->service->post($data));

        return $response;
    }
}
