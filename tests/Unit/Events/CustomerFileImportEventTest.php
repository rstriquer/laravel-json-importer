<?php

namespace Tests\Unit\Events;

use App\Events\CustomerFileImportEvent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerFileImportEventTest extends TestCase
{
    /**
     * @group Events
     * @test
     */
    public function dispatchOk()
    {
        Event::fake();

        CustomerFileImportEvent::dispatch('json','/tmp/file.json');

        Event::assertDispatched(CustomerFileImportEvent::class);
    }
}
