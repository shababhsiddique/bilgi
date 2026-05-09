<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('type'),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('icon')
                    ->label('Icon')
                    ->disk('public')
                    ->image()
                    ->imagePreviewHeight('50')
                    ->visibility('public')
                    ->required(true),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('config'),
                TextInput::make('payment_charge')
                    ->label('Payment Charge')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Amount to charge. Interpreted as flat or percent based on charge type.'),
                Select::make('payment_charge_type')
                    ->label('Charge Type')
                    ->options([
                        'flat'    => 'Flat Amount',
                        'percent' => 'Percentage',
                    ])
                    ->default('flat')
                    ->required(),

            ]);
    }
}
