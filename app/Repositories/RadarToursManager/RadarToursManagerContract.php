<?php

namespace App\Repositories\RadarToursManager;

use App\Models\RadarTour;
use App\Repositories\RadarToursManager\SendRadarTours\SendRadarToursContract;
use App\Repositories\RadarToursManager\StoreRadarTours\StoreRadarToursContract;

interface RadarToursManagerContract
{
    public function __construct(StoreRadarToursContract $storeRadarTours, SendRadarToursContract $sendRadarTours);

    /**
     * @param RadarTour[] $tours
     * @return RadarTour[] updated tours with stored data
     */
    public function processRadarTours(array $tours): array;
}
