<?php

namespace App\Repositories\ImportManager;

interface ImportManagerContract
{
    public function getNextEventId(): string;

    public function freeEventId(string $eventId): void;
}
