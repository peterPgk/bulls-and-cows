<?php

namespace App\Providers;

use App\Game\Generators\Contracts\NumbersGeneratorInterface;
use App\Game\Generators\LimitedNumberGenerator;
use App\Game\Statistics\AttemptsStatistic;
use App\Game\Statistics\Contracts\GameStatisticsInterface;
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
        $this->app->bind(NumbersGeneratorInterface::class, LimitedNumberGenerator::class);
        $this->app->bind(GameStatisticsInterface::class, AttemptsStatistic::class);
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
}
