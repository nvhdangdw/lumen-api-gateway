<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QRBenefitMonolithic;
use Illuminate\Support\Facades\Redis;

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
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function login(Request $request)
    {
        $response = $this->successResponse($this->service->login($request->all()));
        return $response;
    }

    /**
     * @return mixed
     */
    public function info()
    {
        $response = $this->successResponse($this->service->info());
        return $response;
    }

    /**
     * @return mixed
     */
    public function passwordForgot(Request $request)
    {
        $response = $this->successResponse($this->service->passwordForgot($request->all()));
        return $response;
    }
}
