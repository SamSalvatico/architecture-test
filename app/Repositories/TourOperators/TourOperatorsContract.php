<?php

namespace App\Repositories\TourOperators;

use App\Repositories\ImportManager\ImportManagerContract;
use App\Repositories\OperatorToursProcessor\OperatorChooser\OperatorChooserContract;

interface TourOperatorsContract
{
    public function __construct(ImportManagerContract $importManager, OperatorChooserContract $operatorChooser);

    public function importTours(
        string $operatorId,
        array $content
    ): string;
}
