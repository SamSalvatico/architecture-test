<?php

namespace App\Repositories\OperatorToursProcessor\OperatorChooser;

use App\Repositories\OperatorToursProcessor\ProcessorContract;
use App\Repositories\TourOperators\ToImportToursContent;

interface OperatorChooserContract
{
    public function getProcessor(ToImportToursContent $toImportToursContent): ProcessorContract;
}
