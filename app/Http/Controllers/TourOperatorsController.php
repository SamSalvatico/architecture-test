<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessTourRequest;
use Illuminate\Http\JsonResponse;

class TourOperatorsController extends Controller
{
    public function importTours(ProcessTourRequest $request): JsonResponse
    {
        return new JsonResponse();
    }
}
