<?php

namespace App\Providers;

use App\Repositories\ImportManager\ImportManagerContract;
use App\Repositories\ImportManager\ImportManagerStubRepository;
use App\Repositories\OperatorToursProcessor\OperatorChooser\OperatorChooserContract;
use App\Repositories\OperatorToursProcessor\OperatorChooser\SimpleOperatorChooser;
use App\Repositories\RadarToursManager\RadarToursManagerContract;
use App\Repositories\RadarToursManager\SendRadarTours\SendRadarToursContract;
use App\Repositories\RadarToursManager\SendRadarTours\StubSendRadarTours;
use App\Repositories\RadarToursManager\StoreRadarTours\StoreRadarToursContract;
use App\Repositories\RadarToursManager\StoreRadarTours\StubStoreRadarTours;
use App\Repositories\RadarToursManager\StubRadarToursManager;
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
        $this->app->singleton(ImportManagerContract::class, ImportManagerStubRepository::class);
        $this->app->bind(RadarToursManagerContract::class, StubRadarToursManager::class);
        $this->app->bind(TourOperatorsContract::class, TourOperatorsDefaultRepository::class);
        $this->app->bind(OperatorChooserContract::class, SimpleOperatorChooser::class);
        $this->app->bind(SendRadarToursContract::class, StubSendRadarTours::class);
        $this->app->bind(StoreRadarToursContract::class, StubStoreRadarTours::class);
    }
}
