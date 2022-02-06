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
        return [];
    }
}
