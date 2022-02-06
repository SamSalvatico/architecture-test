<?php

namespace App\Jobs;

use App\Repositories\OperatorToursProcessor\OperatourToursProcessorFactory;
use App\Repositories\TourOperators\ToImportToursContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportTours implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private ToImportToursContent $toImportToursContent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ToImportToursContent $toImportToursContent)
    {
        $this->toImportToursContent = $toImportToursContent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var  OperatourToursProcessorFactory $operatorProcessorFactory*/
        $operatorProcessorFactory = app(OperatourToursProcessorFactory::class);
        $operatorProcessorFactory->getRadarTours($this->toImportToursContent);
    }
}
