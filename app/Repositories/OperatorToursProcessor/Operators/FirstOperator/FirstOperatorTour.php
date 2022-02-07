<?php

namespace App\Repositories\OperatorToursProcessor\Operators\FirstOperator;

use App\Models\RadarTour;
use App\Repositories\OperatorToursProcessor\AbstractOperatorTour;
use App\Repositories\OperatorToursProcessor\OperatorTour;

class FirstOperatorTour extends AbstractOperatorTour
{
    private string $firstOperatorId;

    public static function box(array $tourContent): OperatorTour
    {
        return new self($tourContent);
    }

    protected function fromTourContent(): void
    {
        $this->firstOperatorId = $this->tourContent['first_operator_id'];
    }

    public function toRadarTour(): RadarTour
    {
        $outputTour = new RadarTour();
        $outputTour->operator_id = $this->firstOperatorId;
        return $outputTour;
    }
}
