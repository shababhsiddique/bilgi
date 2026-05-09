<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerAddressFactory extends Factory
{
    protected $model = CustomerAddress::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'is_default'  => $this->faker->boolean(30),

            'address_name'        => $this->faker->name(),
            'name'        => $this->faker->name(),
            'phone'       => $this->faker->phoneNumber(),
            'address'     => $this->faker->streetAddress(),
            'city'        => $this->faker->city(),
            'state'       => $this->faker->state(),
            'postcode'    => $this->faker->postcode(),
            'country'     => 'Bangladesh',
        ];
    }


    /**
     * Mark the address as default.
     */
    public function default(): self
    {
        return $this->state(fn () => [
            'is_default' => true,
        ]);
    }
}
