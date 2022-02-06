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
        $enqueued = $this->tryToEnqueueTours($toImportToursContent);

        if ($enqueued) {
            return $eventId;
        }

        $this->importManager->freeEventId($eventId);
        throw new EnqueueTourException("Cannot enqueue this import, retry later...");
    }

    private function tryToEnqueueTours(ToImportToursContent $content): bool
    {
        try {
            $this->enqueueTours($content);
        } catch (Exception) {
            return false;
        }

        return true;
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
