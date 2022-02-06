<?php

namespace App\Repositories\OperatorToursProcessor\OperatorChooser;

use App\Exceptions\TourImport\OperatorNotFoundException;
use App\Repositories\OperatorToursProcessor\Operators\FirstOperator\FirstOperatorProcessor;
use App\Repositories\OperatorToursProcessor\Operators\SecondOperator\SecondOperatorProcessor;
use App\Repositories\OperatorToursProcessor\ProcessorContract;
use App\Repositories\TourOperators\ToImportToursContent;

class SimpleOperatorChooser implements OperatorChooserContract
{
    public function getProcessor(ToImportToursContent $toImportToursContent): ProcessorContract
    {
        switch ($toImportToursContent->operatorId()) {
            case "first":
                return new FirstOperatorProcessor();
            case "second":
                return new SecondOperatorProcessor();
            default:
                throw new OperatorNotFoundException(
                    "A operator with id " . $toImportToursContent->operatorId() . " does not exist!"
                );
        }
    }
}
