<?php

namespace App\Repositories\ImportManager;

class ImportManagerStubRepository implements ImportManagerContract
{
    public function getNextEventId(): string
    {
        $str = rand();
        return md5((string)$str);
    }

    public function freeEventId(string $eventId): void
    {
       // Deleting id from registry
    }
}
