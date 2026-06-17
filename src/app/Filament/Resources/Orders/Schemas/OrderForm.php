<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\PaymentMethod;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('order_number')
                    ->required(),
                Select::make('status')
                    ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'on_hold' => 'On hold',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'refunded' => 'Refunded',
                    'failed' => 'Failed',
                ])
                    ->default('pending')
                    ->required(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('payment_method_id')
                    ->label('Payment Method')
                    ->options(fn () => PaymentMethod::query()
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->required(),
                Select::make('payment_status')
                    ->options([
                    'unpaid' => 'Unpaid',
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'refunded' => 'Refunded',
                    'failed' => 'Failed',
                ])
                    ->default('unpaid')
                    ->required(),
                TextInput::make('payment_trx_id')
                    ->label('Wallet TrxID')
                    ->helperText('Transaction ID submitted by the customer for a bKash / Nagad transfer.')
                    ->maxLength(50),
                TextInput::make('payment_sender_number')
                    ->label('Wallet sender number')
                    ->helperText('The mobile-wallet number the customer paid from.')
                    ->maxLength(20),
                Textarea::make('payment_note')
                    ->columnSpanFull(),

                Textarea::make('notes')
                    ->columnSpanFull(),
                Textarea::make('seller_note')
                    ->columnSpanFull(),
                TextInput::make('consignment_id')
                    ->label('Steadfast consignment ID')
                    ->helperText('Steadfast consignment ID, added once the order is handed over for delivery.')
                    ->maxLength(255),
                TextInput::make('tracking_token')
                    ->label('Steadfast tracking code')
                    ->helperText('Public tracking code from Steadfast, used for the customer tracking link.')
                    ->maxLength(255),
                DateTimePicker::make('placed_at'),
            ]);
    }
}
