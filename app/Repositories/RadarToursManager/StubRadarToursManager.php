<?php

namespace App\Repositories\RadarToursManager;

use App\Models\RadarTour;
use App\Repositories\RadarToursManager\SendRadarTours\SendRadarToursContract;
use App\Repositories\RadarToursManager\StoreRadarTours\StoreRadarToursContract;

class StubRadarToursManager implements RadarToursManagerContract
{
    private StoreRadarToursContract $storeRadarTours;
    private SendRadarToursContract $sendRadarTours;

    public function __construct(StoreRadarToursContract $storeRadarTours, SendRadarToursContract $sendRadarTours)
    {
        $this->storeRadarTours = $storeRadarTours;
        $this->sendRadarTours = $sendRadarTours;
    }

    /**
     * @param RadarTour[] $tours
     * @return RadarTour[] updated tours with stored data
     */
    public function processRadarTours(array $tours): array
    {
        $tours = $this->storeRadarTours->storeTours($tours);
        $this->sendRadarTours->sendTours($tours);

        return $tours;
    }
}
