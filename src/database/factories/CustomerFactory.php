<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Customer>
     */
    protected $model = Customer::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'full_name'  => $this->faker->firstName(),
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => $this->faker->unique()->phoneNumber(),
            'password'   => static::$password ??= Hash::make('password'),
        ];
    }
}
