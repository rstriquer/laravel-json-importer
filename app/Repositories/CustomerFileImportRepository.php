<?php

namespace App\Repositories;

use App\Models\CreditCard;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerFileImportRepository
{
    /**
     * Find customer by email or creates a new one if it does not find it
     * @param array $data
     */
    public function findOrNewCustomer(array &$data) : Customer
    {
        $customer = Customer::whereEmail($data['email'])?->first();
        if (!$customer) {
            return Customer::create($data);
        }
        $customer->fill($data);
        $customer->save();
        return $customer;
    }
    /**
     * Find credit card by card crc32 number or creates a new one if it does not find it
     * - Associates the data in memory if it doesn't find the user in the
     * database. The "save" operation will be handled later by the consumer
     * object.
     * @param array $data
     */
    public function findOrNewCreditCard(array &$data) : CreditCard
    {
        $creditCard = CreditCard::whereNumberCrc32($data['number_crc32'])?->first();
        if (!$creditCard) {
            return app(CreditCard::class, ['attributes' => $data]);
        }
        $creditCard->fill($data);
        return $creditCard;
    }
    /**
     * Save consumer information and their credit card in the database
     * - Try to find the record to update, if not, create a new one;
     * - Performs a transaction to ensure integrity;
     * @param array $customer
     * @param array $creditCard
     */
    public function saveData(array $customer, array $creditCard) : void
    {
        DB::beginTransaction();
        try {
            $customerModel = $this->findOrNewCustomer($customer);
            $creditCardModel = $this->findOrNewCreditCard($creditCard, $customer);
            $customerModel->creditCard()->save($creditCardModel);
            $customerModel->save();
            DB::commit();
        } catch (Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }
}
