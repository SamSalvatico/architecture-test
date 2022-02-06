<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;
use App\Repositories\TourOperators\ToImportToursContent;

interface ProcessorContract
{
    /**
     * @return RadarTour[];
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array;
}
