<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_type')
                    ->label('Type')
                    ->badge(),
                /*TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('sales_price')
                    ->money()
                    ->sortable(),*/
                TextColumn::make('brand')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                /*TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),*/
                IconColumn::make('visible')
                    ->boolean(),
                /*IconColumn::make('track_inventory')
                    ->label('Inventory')
                    ->boolean(),*/
                /*TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),*/
                /*IconColumn::make('is_digital')
                    ->boolean(),*/
                TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
