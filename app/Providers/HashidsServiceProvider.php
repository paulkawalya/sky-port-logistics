<?php

namespace App\Providers;

use Hashids\Hashids;
use Illuminate\Support\ServiceProvider;

class HashidsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Hashids::class, function () {
            return new Hashids(config('app.key'), 10);
        });
    }
}
