<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Contracts\Http\Kernel;
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
         * Показывает ошибки: N+1, $fillable
         */
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            /**
             * Если общий Connect (соеденение) > 500 ? Сообщение в Telegram
             */
            Db::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
                logger()
                    ->channel('telegram')
                    ->debug('whenQueryingForLongerThan: ' . $connection->query()->toSql());
            });

            /**
             * Если отдельный запрос к базе > 100 ? Сообщение в Telegram
             */
            DB::listen(function ($query) {
                if ($query->time > 100) logger()->channel('telegram')->debug('DB::listen: ' . $query->sql, $query->bindings);
            });

            $kernel = app(Kernel::class);

            $kernel->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
                }
            );
        }

    }
}
