<?php

namespace Tests\Feature\artisan;

use App\Events\CustomerFileImportEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CustomerFileImportTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        Log::shouldReceive('info')->andReturnNull();
        Log::shouldReceive('channel')->andReturnSelf();
        Event::fake();

        $this->artisan('import:customerFile whatever.json')->assertSuccessful();

        Event::assertDispatched(CustomerFileImportEvent::class);
    }
}
