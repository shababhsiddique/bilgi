<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Transaction')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('type')
                            ->label('Type')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => $state === 'income' ? 'Cash In' : 'Cash Out')
                            ->color(fn (string $state): string => $state === 'income' ? 'success' : 'danger'),
                        TextEntry::make('amount')
                            ->label('Amount')
                            ->money('BDT'),
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->placeholder('-'),
                        TextEntry::make('wallet')
                            ->label('Wallet')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'bkash' => 'bKash',
                                'nagad' => 'Nagad',
                                default => 'Cash',
                            }),
                        TextEntry::make('transaction_date')
                            ->label('Date')
                            ->dateTime(),
                        TextEntry::make('uuid')
                            ->label('UID')
                            ->copyable(),
                        TextEntry::make('transaction_id')
                            ->label('Reference / Trx ID')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('invoice')
                            ->label('Invoice / Order ref')
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
