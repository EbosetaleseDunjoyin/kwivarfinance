<?php

namespace App\Providers;

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
    public function boot() : void
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null && $this->isFatalError($error['type'])) {
                return redirect()->route('404');
            }
        });
    }

    private function isFatalError($type) : bool
    {
        return in_array($type, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR]);
    }
}
