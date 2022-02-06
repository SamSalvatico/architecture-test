<?php

namespace App\Repositories\OperatorToursProcessor\Operators\FirstOperator;

use App\Repositories\OperatorToursProcessor\ProcessorContract;
use App\Repositories\TourOperators\ToImportToursContent;

class FirstOperatorProcessor implements ProcessorContract
{
    /**
     * @inheritdoc
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array
    {
        return [];
    }
}
