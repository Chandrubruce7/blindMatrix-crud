<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
     * Transform the imported row into a Customer model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $existingCustomer = Customer::where('email', $row['email'])->first();

        if (!$existingCustomer) {
            return new Customer([
                'name'  => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'] ?? null,
            ]);
        }

        return null;
    }
}
