<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;

interface OperatorTour
{
    public function __construct(array $tourContent);
    public static function box(array $tourContent): OperatorTour;
    public function toRadarTour(): RadarTour;
}
