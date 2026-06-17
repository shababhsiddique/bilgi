<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Manual mobile-wallet transfers (bKash, Nagad, ...)
    |--------------------------------------------------------------------------
    |
    | Until a merchant (trade-license) account is available, mobile-wallet
    | payments are accepted as a personal "Send Money" transfer. The customer
    | sends the order total to the shared receive number and submits the
    | transaction ID; the payment is then verified manually from the admin
    | panel. All listed wallets share the same receive number and flow.
    |
    */

    'manual_wallets' => [
        // Payment method codes (PaymentMethod.code) handled as manual transfers.
        'codes' => ['bkash', 'nagad'],

        // Number the customer should "Send Money" to (shared by all wallets).
        'receive_number' => env('PAYMENT_RECEIVE_NUMBER', '01727421885'),

        // Account type shown in the instructions (e.g. "Personal", "Agent").
        'account_type' => env('PAYMENT_ACCOUNT_TYPE', 'Personal'),
    ],

];
