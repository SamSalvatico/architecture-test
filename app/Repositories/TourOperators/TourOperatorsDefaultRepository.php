<?php

namespace App\Repositories\TourOperators;

use App\Exceptions\TourImport\EnqueueTourException;
use App\Exceptions\TourImport\NotValidInputToursException;
use App\Jobs\ImportTours;
use App\Repositories\ImportManager\ImportManagerContract;
use App\Repositories\OperatorToursProcessor\OperatorChooser\OperatorChooserContract;
use App\Repositories\OperatorToursProcessor\OperatourToursProcessorFactory;
use Exception;

class TourOperatorsDefaultRepository implements TourOperatorsContract
{
    private ImportManagerContract $importManager;
    private OperatourToursProcessorFactory $toursProcessorFactory;

    public function __construct(ImportManagerContract $importManager, OperatorChooserContract $operatorChooser)
    {
        $this->importManager = $importManager;
        $this->toursProcessorFactory = new OperatourToursProcessorFactory($operatorChooser);
    }

    public function importTours(
        string $operatorId,
        array $content
    ): string {
        $eventId = $this->importManager->getNextEventId();
        $toImportToursContent = ToImportToursContent::box($operatorId, $content, $eventId);
        try {
            $this->enqueueTours($toImportToursContent);
            return $eventId;
        } catch (EnqueueTourException $ete) {
            $this->importManager->freeEventId($eventId);
            throw $ete;
        }
    }

    private function enqueueTours(ToImportToursContent $content): void
    {
        $this->ensureInputToursAreValid($content);
        ImportTours::dispatch($content);
    }

    /**
     * @throws NotValidInputToursException
     */
    private function ensureInputToursAreValid(ToImportToursContent $content): void
    {
        $processor = $this->toursProcessorFactory->buildProcessor($content);
        $processor->ensureInputContentIsValid($content);
    }
}
