<?php

namespace App\Jobs;

use App\Events\Contracts\FileImportEventInterface;
use App\Factories\CustomerTransformerFactory;
use App\Repositories\CustomerFileImportRepository;
use App\Services\CustomerFileImportService;
use Exception;
use Illuminate\Broadcasting\PrivateChannel;

class CustomerFileImportJob extends AbstractFileImportJob
{
    /**
     * Defines the log output channel of the workflow
     * @var string
     */
    const CHANNEL = 'cronjobs';

    /**
     * Customer file import transformer for the actual file format
     * @var App\Transformers\Contracts\CustomerFileImportTransformerInterface
     */
    private $customerTransformer;

    public function __construct(
        private CustomerFileImportService $service,
        private CustomerFileImportRepository $repository
    )
    {
        parent::__construct(self::CHANNEL);
        $service->setLog($this->log);
    }
    /**
     * @inheritDoc
     */
    public function handle(FileImportEventInterface $event) : void
    {
        try {
            $this->customerTransformer = CustomerTransformerFactory::create(
                $event->getFileFormat()
            );
            $this->validateAndOpenFile(
                $event->getFileFormat(),
                $event->getFileName()
            );
            $this->log->info('file ' . $event->getFileName() . ' validated');
            $iterator = $this->getNextValidRecord();
            foreach ($iterator AS $customer) {
                $creditCard = $customer['credit_card'];
                unset($customer['credit_card']);

                $this->repository->saveData($customer, $creditCard);
            }
        } catch (Exception $err) {
            $this->log->error('Error ' . $err->getMessage() . ' on file '
                . $event->getFileName());
        }
    }
    /**
     * Superficially validate the file format and open its content
     * - The method makes it possible to work with very large files, for it
     * passes on to the operating system the mimetype check and takes this as
     * sufficient for validation
     * @param string $format
     * @param string $fileName
     * @inheritDoc
     * @throws Undocumented function
     */
    private function validateAndOpenFile(string $format, string $fileName) : void
    {
        $this->service->validateFile($format, $fileName);
        $this->service->openFile($fileName);
    }

    private function getNextValidRecord()
    {
        $iterator = $this->service->getNextValidTransformedRecord();
        foreach($iterator AS $content) {
            yield $this->customerTransformer->transform($content);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('CustomFileImport');
    }
}
