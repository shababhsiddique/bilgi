<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('OrderInfo')
                    ->schema([
                        TextEntry::make('customer_id')
                            ->numeric(),
                        TextEntry::make('order_number'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('subtotal')
                            ->numeric(),
                        TextEntry::make('discount_amount')
                            ->numeric(),
                        TextEntry::make('shipping_amount')
                            ->numeric(),
                        TextEntry::make('total_amount')
                            ->numeric(),
                        TextEntry::make('paymentMethod.name')
                            ->label('Payment Method')
                            ->placeholder('-'),
                        TextEntry::make('payment_status')
                            ->badge(),
                        TextEntry::make('payment_trx_id')
                            ->label('Wallet TrxID')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('payment_sender_number')
                            ->label('Wallet sender number')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('payment_note')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('seller_note')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('consignment_id')
                            ->label('Steadfast consignment ID')
                            ->placeholder('-'),
                        TextEntry::make('tracking_token')
                            ->label('Steadfast tracking code')
                            ->placeholder('-'),
                        TextEntry::make('placed_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('Order Items')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('product_name')
                                    ->label('Product'),

                                TextEntry::make('quantity')
                                    ->label('Qty')
                                    ->numeric(),

                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('BDT', divideBy: 100),

                                TextEntry::make('line_total')
                                    ->label('Line Total')
                                    ->money('BDT', divideBy: 100),
                            ])
                            ->visible(fn (Order $record) => $record->items()->exists()),
                    ]),

                // Shipping address card
                Section::make('Shipping Information')
                    ->schema([
                        TextEntry::make('shippingAddress.name')
                            ->label('Name')
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.phone')
                            ->label('Phone')
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.address')
                            ->label('Address')
                            ->columnSpanFull()
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.city')
                            ->label('City')
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.state')
                            ->label('State')
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.postcode')
                            ->label('Postcode')
                            ->placeholder('-'),
                        TextEntry::make('shippingAddress.country')
                            ->label('Country')
                            ->placeholder('-'),
                    ])
                    ->visible(fn (Order $record) => $record->shippingAddress !== null),

                // Billing address card
                Section::make('Billing Information')
                    ->schema([
                        TextEntry::make('billingAddress.name')
                            ->label('Name')
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.phone')
                            ->label('Phone')
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.address')
                            ->label('Address')
                            ->columnSpanFull()
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.city')
                            ->label('City')
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.state')
                            ->label('State')
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.postcode')
                            ->label('Postcode')
                            ->placeholder('-'),
                        TextEntry::make('billingAddress.country')
                            ->label('Country')
                            ->placeholder('-'),
                    ])
                    ->visible(fn (Order $record) => $record->billingAddress !== null),



                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
