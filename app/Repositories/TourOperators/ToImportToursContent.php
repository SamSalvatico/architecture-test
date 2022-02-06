<?php

namespace App\Repositories\TourOperators;

class ToImportToursContent
{
    private string $operatorId;
    private array $toursContent;
    private string $eventId;

    public function __construct(string $operatorId, array $toursContent, string $eventId)
    {
        $this->operatorId = $operatorId;
        $this->toursContent = $toursContent;
        $this->eventId = $eventId;
    }

    public static function box(string $operatorId, array $toursContent, string $eventId): ToImportToursContent
    {
        return new ToImportToursContent($operatorId, $toursContent, $eventId);
    }

    public function operatorId(): string
    {
        return $this->operatorId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function toursContent(): array
    {
        return $this->toursContent;
    }
}
