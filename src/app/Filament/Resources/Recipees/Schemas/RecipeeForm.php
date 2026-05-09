<?php

namespace App\Filament\Resources\Recipees\Schemas;

use App\Models\ProductVariant;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RecipeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('product_variant_id')
                    ->label('Product Variant')
                    ->relationship(
                        name: 'productVariant',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->with('product'),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (ProductVariant $record): string => ($record->product?->name ?? 'Unknown Product') . ' - ' . $record->name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('material_key')
                            ->label('Material')
                            ->relationship('material', 'displayname')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(2)
                    ->addActionLabel('Add recipee item')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ]);
    }
}
