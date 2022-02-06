<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;
use App\Repositories\TourOperators\ToImportToursContent;

abstract class AbstractOperatorTour implements OperatorTour
{
    protected ToImportToursContent $toImportToursContent;

    public function __construct(ToImportToursContent $toImportToursContent)
    {
        $this->toImportToursContent = $toImportToursContent;
    }

    abstract public static function box(ToImportToursContent $toImportToursContent): OperatorTour;

    abstract public function toRadarTour(): RadarTour;
}
