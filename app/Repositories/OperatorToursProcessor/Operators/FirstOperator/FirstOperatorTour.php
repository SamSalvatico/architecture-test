<?php

namespace App\Repositories\OperatorToursProcessor\Operators\FirstOperator;

use App\Models\RadarTour;
use App\Repositories\OperatorToursProcessor\AbstractOperatorTour;
use App\Repositories\OperatorToursProcessor\OperatorTour;

class FirstOperatorTour extends AbstractOperatorTour
{
    public static function box(array $contentTour): OperatorTour
    {
        return new self($contentTour);
    }

    protected function fromTourContent(): void
    {
    }

    public function toRadarTour(): RadarTour
    {
        return new RadarTour();
    }
}
