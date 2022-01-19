<?php

namespace App\Providers;

use App\Events\CustomerFileImportEvent;
use App\Jobs\CustomerFileImportJob;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerFileImportEvent::class => [
            CustomerFileImportJob::class,
        ],
    ];
}
