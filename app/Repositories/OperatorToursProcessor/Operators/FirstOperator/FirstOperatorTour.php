<?php

namespace App\Repositories\OperatorToursProcessor\Operators\FirstOperator;

use App\Models\RadarTour;
use App\Repositories\OperatorToursProcessor\AbstractOperatorTour;
use App\Repositories\OperatorToursProcessor\OperatorTour;
use App\Repositories\TourOperators\ToImportToursContent;

class FirstOperatorTour extends AbstractOperatorTour
{
    public static function box(ToImportToursContent $toImportToursContent): OperatorTour
    {
        return new self($toImportToursContent);
    }

    public function toRadarTour(): RadarTour
    {
        return new RadarTour();
    }
}
