<?php

namespace App\Repositories\OperatorToursProcessor\Operators\SecondOperator;

use App\Models\RadarTour;
use App\Repositories\OperatorToursProcessor\AbstractOperatorTour;
use App\Repositories\OperatorToursProcessor\OperatorTour;
use App\Repositories\TourOperators\ToImportToursContent;

class SecondOperatorTour extends AbstractOperatorTour
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
