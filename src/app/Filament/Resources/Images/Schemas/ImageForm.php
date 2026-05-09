<?php

namespace App\Filament\Resources\Images\Schemas;

use App\Models\Tag;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('url')
                    ->url()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpanFull(),
                TagsInput::make('tags')
                    ->separator(',')
                    ->splitKeys(['Tab', ' ', ','])
                    ->suggestions(fn (): array => Tag::query()->pluck('name')->all())
                    ->columnSpanFull(),
                Toggle::make('visible')
                    ->default(true)
                    ->required(),
            ]);
    }
}
