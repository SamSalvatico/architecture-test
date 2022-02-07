<?php

namespace App\Repositories\RadarToursManager\StoreRadarTours;

class StubStoreRadarTours implements StoreRadarToursContract
{
    /**
     * @intheritdoc
     */
    public function storeTours(array $tours): array
    {
        // Store tours here
        return $tours;
    }
}
