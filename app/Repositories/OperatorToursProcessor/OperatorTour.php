<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;

interface OperatorTour
{
    public function __construct(array $inputTour);
    public static function box(array $inputTour): OperatorTour;
    public function toRadarTour(): RadarTour;
}
