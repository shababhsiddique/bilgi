<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Category;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->options(function (?Category $record): array {
                        return Category::query()
                            ->when(
                                $record?->id,
                                fn ($query) => $query->where('id', '!=', $record->id),
                            )
                            ->pluck('name', 'id')
                            ->toArray();
                    }),
                Toggle::make('visible')
                    ->required(),
            ]);
    }
}
