<?php

namespace App\Repositories\TourOperators;

use App\Exceptions\TourImport\EnqueueTourException;
use App\Jobs\ImportTours;
use App\Repositories\ImportManager\ImportManagerContract;
use Exception;

class TourOperatorsDefaultRepository implements TourOperatorsContract
{
    private ImportManagerContract $importManager;

    public function __construct(ImportManagerContract $importManager)
    {
        $this->importManager = $importManager;
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
        ImportTours::dispatch($content);
    }
}
