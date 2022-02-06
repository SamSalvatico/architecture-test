<?php

namespace App\Repositories\ImportManager;

class ImportManagerStubRepository implements ImportManagerContract 
{    
    public function getNextEventId(): string {
        $str = rand();
        return md5($str);
    }

    public function freeEventId(string $eventId): void {
        Log::debug("Deleting event id from registry...");
    }
}
