<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Models\RadarTour;
use App\Repositories\OperatorToursProcessor\OperatorChooser\OperatorChooserContract;
use App\Repositories\TourOperators\ToImportToursContent;

class OperatourToursProcessorFactory
{
    private OperatorChooserContract $operatorChooser;

    public function __construct(OperatorChooserContract $operatorChooser)
    {
        $this->operatorChooser = $operatorChooser;
    }

    protected function buildProcessor(ToImportToursContent $toImportToursContent): ProcessorContract
    {
        return $this->operatorChooser->getProcessor($toImportToursContent);
    }

    /**
     * @return RadarTour[]
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array
    {
        $processor = $this->buildProcessor($toImportToursContent);
        return $processor->getRadarTours($toImportToursContent);
    }
}
