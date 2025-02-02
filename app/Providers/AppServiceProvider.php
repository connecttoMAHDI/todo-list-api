<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
         * Disable the 'data' wrapping for all resources
         */
        JsonResource::withoutWrapping();

        /**
         * Logger to Log every query hit the DB
         *
         * logs are stored in 'storage/logs'
         */
        if (env('APP_ENV') === 'local') {
            DB::listen(function ($query) {
                $log = json_encode([
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time.' ms',
                ]);

                Log::channel('daily')->info($log);
            });
        }
    }
}
