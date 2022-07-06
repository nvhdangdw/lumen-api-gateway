<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QRBenefitMonolithic;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class QRBenefitMonolithicController extends Controller
{
    /**
     * @var \App\Services\QRBenefitMonolithic
     */
    protected $service;

    /**
     * OrderController constructor.
     *
     * @param \App\Services\QRBenefitMonolithic $service
     */
    public function __construct(QRBenefitMonolithic $service)
    {
        $this->service = new $service;
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
