<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\UpdateLocationRequest;
use App\Services\DriverLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{
    public function update(UpdateLocationRequest $request, DriverLocationService $locationService): JsonResponse
    {
        $locationService->updateLocation(Auth::user(), $request->validated('lat'), $request->validated('lng'));

        return response()->json([
            'status' => 'location_updated',
        ]);
    }
}