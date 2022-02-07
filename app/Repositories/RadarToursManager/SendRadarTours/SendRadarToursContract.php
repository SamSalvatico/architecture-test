<?php

namespace App\Repositories\RadarToursManager\SendRadarTours;

use App\Models\RadarTour;

interface SendRadarToursContract
{
    /**
     * @param RadarTour[] $tours
     */
    public function sendTours(array $tours): void;
}
