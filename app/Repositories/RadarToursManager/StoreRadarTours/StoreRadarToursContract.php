<?php

namespace App\Repositories\RadarToursManager\StoreRadarTours;

use App\Models\RadarTour;

interface StoreRadarToursContract
{
    /**
     * @param RadarTour[] $tours
     * @return RadarTour[] updated tours with stored data
     */
    public function storeTours(array $tours): array;
}
