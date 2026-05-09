<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Customer
    {
        Validator::make($input, [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'string',
                'max:12',
                'required',
                Rule::unique(Customer::class),
            ],'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(Customer::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $customer = Customer::create([
            'full_name' => $input['full_name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
        ]);

        // Merge guest cart into this new customer
        app(CartService::class)->mergeSessionCartInto($customer);

        return $customer;
    }
}
