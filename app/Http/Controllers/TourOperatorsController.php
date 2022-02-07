<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessTourRequest;
use App\Repositories\TourOperators\TourOperatorsContract;
use Illuminate\Http\JsonResponse;

class TourOperatorsController extends Controller
{
    private TourOperatorsContract $tourOperators;

    public function __construct(TourOperatorsContract $tourOperators)
    {
        $this->tourOperators = $tourOperators;
    }

    public function importTours(ProcessTourRequest $request): JsonResponse
    {
        $validatedBody = $request->validated();
        $eventId = $this->tourOperators->importTours($validatedBody["operator_id"], $validatedBody["tours_data"]);
        return new JsonResponse(["event_id" => $eventId]);
    }
}
