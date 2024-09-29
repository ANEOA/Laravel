<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Показывает ошибку N+1
         */
        Model::preventLazyLoading(!app()->isProduction());
        /**
         * Показывает ошибку метода fillable
         */
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
        /**
         * Запросы к базе данных выполняются больше указ. секунд (500)
         */
        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            // Notify development team...
        });
    }
}
