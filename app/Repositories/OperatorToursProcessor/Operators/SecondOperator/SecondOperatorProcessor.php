<?php

namespace App\Repositories\OperatorToursProcessor\Operators\SecondOperator;

use App\Repositories\OperatorToursProcessor\ProcessorContract;
use App\Repositories\TourOperators\ToImportToursContent;

class SecondOperatorProcessor implements ProcessorContract
{
    /**
     * @inheritdoc
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array
    {
        $output = [];
        foreach ($toImportToursContent->toursContent() as $currentTour) {
            array_push($output, SecondOperatorTour::box($currentTour)->toRadarTour());
        }
        return $output;
    }

    /**
     * @inheritdoc
     */
    public function ensureInputContentIsValid(ToImportToursContent $toImportToursContent): void
    {
    }
}
