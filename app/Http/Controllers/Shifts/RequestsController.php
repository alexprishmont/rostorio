<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shifts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftRequestsResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RequestsController extends Controller
{
    public function show(User $user): AnonymousResourceCollection
    {
        return ShiftRequestsResource::collection($user->shiftRequests()->forIncomingMonth()->get());
    }
}
