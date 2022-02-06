<?php

namespace App\Repositories\TourOperators;

use App\Exceptions\TourImport\EnqueueTourException;
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
        $enqueued = $this->tryToEnqueueTours($operatorId, $content, $eventId);

        if($enqueued) 
        {
            return $eventId;
        }

        $this->importManager->freeEventId($eventId);
        throw new EnqueueTourException("Cannot enqueue this import, retry later...");
    }

    private function tryToEnqueueTours(string $operatorId, array $content, string $eventId): bool {
        try {
            $this->enqueueTours($operatorId, $content, $eventId);
        } catch(Exception) {
            return false;
        }
    }

    private function enqueueTours(string $operatorId, array $content, string $eventId): void {

    }
}
