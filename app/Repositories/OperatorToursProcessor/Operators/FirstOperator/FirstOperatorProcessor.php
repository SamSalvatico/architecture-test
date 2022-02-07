<?php

namespace App\Repositories\OperatorToursProcessor\Operators\FirstOperator;

use App\Exceptions\TourImport\NotValidInputToursException;
use App\Repositories\OperatorToursProcessor\ProcessorContract;
use App\Repositories\TourOperators\ToImportToursContent;

class FirstOperatorProcessor implements ProcessorContract
{
    /**
     * @inheritdoc
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array
    {
        $output = [];
        foreach ($toImportToursContent->toursContent() as $currentTour) {
            array_push($output, FirstOperatorTour::box($currentTour)->toRadarTour());
        }
        return $output;
    }

    /**
     * @inheritdoc
     */
    public function ensureInputContentIsValid(ToImportToursContent $toImportToursContent): void
    {
        foreach ($toImportToursContent->toursContent() as $currentTour) {
            $this->ensureIsCurrentTourValid($currentTour);
        }
    }

    private function ensureIsCurrentTourValid(array $currentTour): void
    {
        if (!key_exists("first_operator_id", $currentTour)) {
            throw new NotValidInputToursException("You must set first_operator_id field");
        }
    }
}
