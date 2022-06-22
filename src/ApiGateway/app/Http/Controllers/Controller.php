<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponse;
}
