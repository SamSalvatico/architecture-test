<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;

abstract class AbstractOperatorTour implements OperatorTour
{
    protected array $tourContent;

    public function __construct(array $tourContent)
    {
        $this->tourContent = $tourContent;
        $this->fromTourContent();
    }

    abstract protected function fromTourContent(): void;
    abstract public static function box(array $tourContent): OperatorTour;

    abstract public function toRadarTour(): RadarTour;
}
