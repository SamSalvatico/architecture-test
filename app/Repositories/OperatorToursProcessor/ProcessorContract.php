<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;

interface ProcessorContract
{
    public function getRadarTour(array $inputTour): RadarTour;
    /**
     * @return RadarTour[];
    */
    public function getRadarTours(array $inputTour): array;
    public function getRadarTourFromOperatorTour(OperatorTour $inputTour): RadarTour;
    /**
     * @param OperatorTour[] $inputTours
     * @return RadarTour[];
     */
    public function getRadarToursFromOperatorTours(array $inputTours): array;
}
