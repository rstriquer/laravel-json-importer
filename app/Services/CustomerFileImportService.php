<?php

namespace App\Services;

use App\Exceptions\InvalidJsonFormat;
use App\Traits\ValidateUTF8File;
use JsonMachine\Items;
use Psr\Log\LoggerInterface;
use stdClass;

class CustomerFileImportService
{
    use ValidateUTF8File;

    private $customerFilePointer;
    private string $datFileName;
    protected LoggerInterface $log;

    /**
     * Uses JsonMachine's Items::fromFile pattern to open a file
     * @param string $fileName
     */
    public function openFile(string $fileName)
    {
        $this->customerFilePointer = Items::fromFile($fileName);
        $this->datFileName = substr($fileName, 0, strrpos($fileName, '.')) . '.dat';
    }
    /**
     * Used to inject a log object to be used internally
     * @param LoggerInterface $log
     */
    public function setLog(LoggerInterface $log)
    {
        $this->log = $log;
    }
    /**
     * Returns the next information record containing valid data.
     * - Make the process such that any time it can be truncated (e.g., by a
     * SIGTERM, power outage, etc.), it can continue in a robust, reliable
     * manner exactly where it last left off (without duplicating data);
     * - Uses the same name of the original file to build a ".dat" file to
     * control interruption of work continuity saving last register index
     * position in the file;
     * @throws \JsonMachine\Exception\UnexpectedEndSyntaxErrorException
     */
    public function getNextValidTransformedRecord()
    {
        $fileindex = 0;
        if (is_file($this->datFileName)) {
            $fileindex = (int) file_get_contents($this->datFileName);
        }
        if ($fileindex != 0) {
            $this->log->info('repositioning pointer to ' . $fileindex
                . ' record');
        }
        $actualIndex = 0;
        foreach ($this->customerFilePointer AS $item) {
            if ($actualIndex < $fileindex) {
                $actualIndex++;
                continue;
            }
            $fileindex++;
            if ($fileindex % 100 === 0) {
                $this->log->info('-> processing ' . $fileindex . ' record');
            }
            file_put_contents($this->datFileName, $fileindex);
            if (!$this->validateFields($item)) {
                continue;
            }
            yield $item;
        }
    }
    /**
     * Verify if file is of UTF8 valid format
     * - The method makes it possible to work with very large files, for it
     * passes on to the operating system the mimetype check and takes this as
     * sufficient for validation;
     * @throws InvalidJsonFormat
     */
    public function validateFile(string $format, string $fileName) : bool
    {
        if (!$this->isUTF8File($fileName)) {
            throw app(InvalidJsonFormat::class);
        }
        return true;
    }
    /**
     * Validate record format and if its content could be used by the system
     * @param \stdClass $payload
     */
    private function validateFields(stdClass $payload) : bool
    {
        return $this->validateCustomer($payload)
            && $this->validateCreditCard($payload->credit_card);
    }
    /**
     * Return true if customer data is valid
     * - Account must be of int type;
     * - To be valid, records must have at least name, address, description,
     * email (with correct format) and an account number;
     * - Only customers between 18 and 65 (or unknown) of age are valid;
     * @param \stdClass $payload
     */
    private function validateCustomer(stdClass $payload) : bool
    {
        if (in_array(null, [
            $payload->name,
            $payload->address,
            $payload->description,
            $payload->email,
            $payload->account,
        ])) {
            return false;
        }
        if ( ! filter_var($payload->email, FILTER_VALIDATE_EMAIL) ) {
            return false;
        }
        if ( $payload->account != (int) $payload->account ) {
            return false;
        }

        if ($payload->date_of_birth === null) {
            return true;
        }
        $limitInferior = now()->subYears(18);
        $limitInferior->hour = 0;
        $limitInferior->minute = 0;
        $limitInferior->second = 0;
        $limitSuperior = now()->subYears(65);
        $limitSuperior->hour = 0;
        $limitSuperior->minute = 0;
        $limitSuperior->second = 0;
        if (
            $payload->date_of_birth >= $limitInferior
            && $limitSuperior <= $payload->date_of_birth
        ) {
            return true;
        }

        return false;
    }
    /**
     * Return true if credit card data is valid
     * - All fields are required
     * @param \stdClass $payload
     */
    private function validateCreditCard(stdClass $payload) : bool
    {
        if (in_array(null, [
            $payload->type,
            $payload->number,
            $payload->name,
            $payload->expirationDate,
        ])) {
            return false;
        }
        if ( $payload->number != (int) $payload->number ) {
            return false;
        }
        return true;
    }
}
