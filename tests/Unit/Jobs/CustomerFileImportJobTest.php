<?php

namespace Tests\Unit\Jobs;

use App\Events\CustomerFileImportEvent;
use App\Jobs\CustomerFileImportJob;
use App\Repositories\CustomerFileImportRepository;
use App\Services\CustomerFileImportService;
use App\Transformers\CustomerJsonFormatTransformer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use stdClass;
use Tests\TestCase;

class CustomerFileImportJobTest extends TestCase
{
    /**
     * @group Jobs
     * @test
     */
    public function ListeningOk()
    {
        Event::fake();
        Event::assertListening(
            CustomerFileImportEvent::class,
            CustomerFileImportJob::class
        );
    }
    /**
     * @group Jobs
     * @test
     */
    public function handleOk()
    {
        Log::shouldReceive('info')->andReturnNull();
        Log::shouldReceive('channel')->andReturnSelf();

        $transformerMock = $this->partialMock(CustomerJsonFormatTransformer::class);
        $transformerMock->shouldReceive('transform')
            ->andReturn(['name' => '', 'credit_card' => [0,1]]);

        $serviceMock = $this->partialMock(CustomerFileImportService::class);
        $serviceMock->shouldReceive('validateFile')->andReturnTrue();
        $serviceMock->shouldReceive('openFile')->andReturnNull();
        $serviceMock->shouldReceive('getNextValidTransformedRecord')
            ->andReturn([app(stdClass::class), app(stdClass::class)]);

        $repositoryMock = $this->partialMock(CustomerFileImportRepository::class);
        $repositoryMock->shouldReceive('saveData')->andReturnNull();

        $test = app(
            CustomerFileImportJob::class,
            ['service' => $serviceMock, 'reposiroty' => $repositoryMock]
        );

        $event = app(
            CustomerFileImportEvent::class,
            ['fileFormat' => 'json', 'fileName' => 'whatever.json']
        );

        $result = $test->handle($event);
    }
}
