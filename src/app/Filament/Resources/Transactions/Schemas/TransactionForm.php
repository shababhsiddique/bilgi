<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'income'  => 'Cash In',
                        'expense' => 'Cash Out',
                    ])
                    ->default('income')
                    ->required(),

                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('৳')
                    ->required(),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Category name')
                            ->required(),
                    ]),

                Select::make('wallet')
                    ->label('Wallet')
                    ->options([
                        'cash'  => 'Cash',
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                    ])
                    ->default('cash')
                    ->required(),

                DateTimePicker::make('transaction_date')
                    ->label('Date')
                    ->default(now())
                    ->required(),

                TextInput::make('transaction_id')
                    ->label('Reference / Trx ID')
                    ->maxLength(255),

                TextInput::make('invoice')
                    ->label('Invoice / Order ref')
                    ->maxLength(255),

                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),
            ]);
    }
}
