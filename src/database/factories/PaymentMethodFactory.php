<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        return [
            'name'        => 'Cash on Delivery',
            'code'        => 'cod',
            'type'        => 'cash',
            'description' => 'Pay with cash upon delivery.',
            'icon'        => null,
            'sort_order'  => 1,
            'is_active'   => true,
            'config'      => null,
        ];
    }

    /**
     * bKash variant
     */
    public function bkash()
    {
        return $this->state(function () {
            return [
                'name'        => 'bKash',
                'code'        => 'bkash',
                'type'        => 'mobile_wallet',
                'description' => 'Pay securely via bKash.',
                'icon'        => null,
                'sort_order'  => 2,
                'is_active'   => true,
                'config'      => "key:testasdasdf,secret:asdfagsd",
            ];
        });
    }
}
