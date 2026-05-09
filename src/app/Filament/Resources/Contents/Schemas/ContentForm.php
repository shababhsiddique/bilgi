<?php

namespace App\Filament\Resources\Contents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content Information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('content_key')
                                ->label('Content Key')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('e.g., shop_banner_data, header_menu'),

                            Select::make('product_id')
                                ->label('Related Product')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ]),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                    ]),

                Section::make('Content Data')
                    ->description('Enter raw HTML, JSON, or plain text content. This will be stored directly in the database.')
                    ->schema([
                        Textarea::make('content_data')
                            ->label('Content Data')
                            ->required()
                            ->rows(15)
                            ->columnSpanFull()
                            ->placeholder('Enter HTML, JSON, or text content here...')
                            ->helperText('You can enter raw HTML tags, JSON objects, or plain text. No automatic encoding will be applied.')
                            ->extraAttributes([
                                'style' => 'font-family: monospace; font-size: 14px;'
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
