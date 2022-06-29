<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QRBenefitService;
use Illuminate\Support\Facades\Redis;

class QRBenefitController extends Controller
{
    /**
     * @var \App\Services\QRBenefitService
     */
    protected $qrBenefitService;

    /**
     * OrderController constructor.
     *
     * @param \App\Services\QRBenefitService $qrBenefitService
     */
    public function __construct(QRBenefitService $qrBenefitService)
    {
        $this->qrBenefitService = $qrBenefitService;
    }

    /**
     * @return mixed
     */
    public function login(Request $request)
    {
        $response = $this->successResponse($this->qrBenefitService->login($request->all()));
        return $response;
    }

    /**
     * @return mixed
     */
    public function info()
    {
        $response = $this->successResponse($this->qrBenefitService->info());
        return $response;
    }
}
