<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;
use App\Repositories\TourOperators\ToImportToursContent;

interface OperatorTour
{
    public function __construct(ToImportToursContent $toImportToursContent);
    public static function box(ToImportToursContent $toImportToursContent): OperatorTour;
    public function toRadarTour(): RadarTour;
}
