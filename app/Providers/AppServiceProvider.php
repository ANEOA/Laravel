<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Http\Response;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(500)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Полегче ;)', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

        if (app()->isProduction()) {
            /**
             * Если общий Connect (соеденение) > 500 миллисекунд, то отправляем сообщение в Telegram
             */
            Db::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
                logger()
                    ->channel('telegram')
                    ->debug('whenQueryingForLongerThan: ' . $connection->query()->toSql());
            });

            /**
             * Если отдельный запрос к базе больше 100 миллисекунд, то отправляем сообщение в Telegram
             */
            DB::listen(function ($query) {
                if ($query->time > 100) logger()
                    ->channel('telegram')
                    ->debug('DB::listen: ' . $query->sql, $query->bindings);
            });

            /**
             * Если запрост длится больше 4 секунда, то отправляем сообщение в Telegram
             */
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
