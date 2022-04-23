<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shifts;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableShiftRequestTypesResource;

class AvailableShiftRequestsTypesController extends Controller
{
    public function index(): AvailableShiftRequestTypesResource
    {
        return new AvailableShiftRequestTypesResource(null);
    }
}
