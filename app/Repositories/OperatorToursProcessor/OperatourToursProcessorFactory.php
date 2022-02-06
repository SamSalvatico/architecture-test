<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;

abstract class OperatourToursProcessorFactory
{
    abstract public function buildProcessor(): ProcessorContract;

    /**
     * @return RadarTour[]
     */
    public function getRadarTours(array $inputTours): array
    {
        $processor = $this->buildProcessor();
        return $processor->getRadarTours($inputTours);
    }
}
