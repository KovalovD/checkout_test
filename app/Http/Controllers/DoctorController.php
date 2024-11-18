<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\DoctorsNetworkDTO;
use App\Http\Requests\DoctorsNetworkRequest;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    public function __construct(private readonly DoctorService $doctorService)
    {
    }

    public function networkAggregates($id, DoctorsNetworkRequest $request): JsonResponse
    {
        // Get query parameters
        $specializationName = $request->validated('specialization');
        $minYoe = $request->validated('min_yoe');
        $maxYoe = $request->validated('max_yoe');



        return response()->json(
            $this->doctorService->findNetworkById(
                $id,
                new DoctorsNetworkDTO($specializationName, $minYoe, $maxYoe)
            )
        );
    }


}
