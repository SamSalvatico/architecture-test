<?php

namespace App\Repositories\TourOperators;

use App\Repositories\ImportManager\ImportManagerContract;

interface TourOperatorsContract
{
    public function __construct(ImportManagerContract $importManager);

    public function importTours(
        string $operatorId,
        array $content
    ): string;
}
