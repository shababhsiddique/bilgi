<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query, $livewire): Builder {
                [$start, $end] = TransactionResource::resolveDateRange(
                    property_exists($livewire, 'filters') ? $livewire->filters : null
                );

                return $query->whereBetween('transaction_date', [$start, $end]);
            })
            ->defaultSort('transaction_date', 'desc')
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'income' ? 'Cash In' : 'Cash Out')
                    ->color(fn (string $state): string => $state === 'income' ? 'success' : 'danger'),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('wallet')
                    ->label('Wallet')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        default => 'Cash',
                    }),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('invoice')
                    ->label('Invoice')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('transaction_id')
                    ->label('Reference')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('notes')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
