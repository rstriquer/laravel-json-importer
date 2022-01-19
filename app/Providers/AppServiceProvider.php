<?php

namespace App\Providers;

use App\Transformers\Contracts\CustomerFileImportTransformerInterface;
use App\Transformers\CustomerJsonFormatTransformer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FileImportEventInterface::class,
            CustomerFileImportEvent::class
        );
        $this->app->bind(
            CustomerFileImportTransformerInterface::class,
            CustomerJsonFormatTransformer::class
        );
    }
}
