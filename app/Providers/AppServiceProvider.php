<?php

namespace App\Providers;

use App\Repositories\ImportManager\ImportManagerContract;
use App\Repositories\ImportManager\ImportManagerStubRepository;
use App\Repositories\TourOperators\TourOperatorsContract;
use App\Repositories\TourOperators\TourOperatorsDefaultRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindCustomRepositories();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindCustomRepositories(): void
    {
        $this->app->bind(ImportManagerContract::class, ImportManagerStubRepository::class);
        $this->app->bind(TourOperatorsContract::class, TourOperatorsDefaultRepository::class);
    }
}
