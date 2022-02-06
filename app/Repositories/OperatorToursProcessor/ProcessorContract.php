<?php

namespace App\Repositories\OperatorToursProcessor;

use App\Exceptions\TourImport\NotValidInputToursException;
use App\Models\RadarTour;
use App\Repositories\TourOperators\ToImportToursContent;

interface ProcessorContract
{
    /**
     * @return RadarTour[];
     */
    public function getRadarTours(ToImportToursContent $toImportToursContent): array;

    /**
     * @throws NotValidInputToursException
     */
    public function ensureInputContentIsValid(ToImportToursContent $toImportToursContent): void;
}
