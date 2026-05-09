<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Info')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->disk('public')
                            ->image()
                            ->imagePreviewHeight('250')
                            ->visibility('public')
                            ->required(true),

                        Grid::make(3)->schema([
                            Select::make('product_type')
                                ->options(['goods' => 'Goods', 'service' => 'Service'])
                                ->default('goods')
                                ->required(),

                            Select::make('categories')
                                ->label('Categories')
                                ->relationship(
                                    name: 'categories',
                                    titleAttribute: 'name',
                                )
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->columnSpan(2),
                        ]),

                        TextInput::make('name')
                            ->required(),

                        TextInput::make('slug')
                            ->required(),

                        Textarea::make('description')
                            ->columnSpanFull(),

                        Textarea::make('long_description')
                            ->columnSpanFull(),

                        Grid::make(3)->schema([
                            Toggle::make('visible')
                                ->required(),
                            Toggle::make('is_digital')
                                ->required(),
                            Toggle::make('track_inventory')
                                ->label('Track Inventory')
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Inventory')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship('variants')
                            ->grid(2)
                            ->defaultItems(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Variant Name')
                                    ->placeholder('e.g. Red / Large')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(191),

                                TextInput::make('barcode')
                                    ->label('Barcode')
                                    ->maxLength(191),

                                Grid::make(2)->schema([
                                    TextInput::make('cost')
                                        ->numeric()
                                        ->required()
                                        ->label('Cost'),

                                    TextInput::make('sales_price')
                                        ->numeric()
                                        ->required()
                                        ->label('Sales Price'),

                                    TextInput::make('stock')
                                        ->numeric()
                                        ->label('Stock')
                                        ->required(),

                                    TextInput::make('min_stock')
                                        ->numeric()
                                        ->label('Base Min Stock')
                                        ->required(),
                                ]),

                                FileUpload::make('image')
                                    ->label('Image')
                                    ->disk('public')
                                    ->image()
                                    ->imagePreviewHeight('250')
                                    ->visibility('public')
                                    ->required(true),

                                TableRepeater::make('product_attributes')
                                    ->label('Attributes')
                                    ->relationship('productAttributes')
                                    ->columnSpanFull()
                                    ->schema([
                                        TextInput::make('attribute_name')
                                            ->label('Attribute Name')
                                            ->placeholder('e.g. Color, Size')
                                            ->required(),
                                        TextInput::make('attribute_value')
                                            ->label('Attribute Value')
                                            ->placeholder('e.g. Black, 38')
                                            ->required(),
                                    ])
                                    ->addActionLabel('Add attribute')
                                    ->reorderable(false)
                                    ->collapsible(false),
                            ])
                            ->addActionLabel('Add variant'),
                    ])
                    ->columnSpanFull(),

                Section::make('Media')
                    ->schema([
                        Repeater::make('product_medias')
                            ->label('Gallery')
                            ->relationship('medias') // adjust to your actual relation name on Product model
                            ->grid(2)
                            ->schema([
                                FileUpload::make('media_url')
                                    ->label('File')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->imagePreviewHeight('150')
                                    ->openable()
                                    ->downloadable()
                                    ->required(),

                                Select::make('media_type')
                                    ->label('Type')
                                    ->options([
                                        'image' => 'Image',
                                        'video' => 'Video',
                                        'other' => 'Other',
                                    ])
                                    ->default('image')
                                    ->required(),
                            ])
                            ->addActionLabel('Add media'),
                    ]),

                Section::make('Additional Details')
                    ->schema([
                        Tabs::make('Additional Details')
                            ->tabs([
                                Tab::make('Marketing')
                                    ->schema([
                                        TextInput::make('brand'),
                                        TextInput::make('age_group')
                                            ->label('Age Group')
                                            ->maxLength(10),
                                        TextInput::make('sort_order')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('ribbon_text'),
                                        Textarea::make('notes')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('SEO')
                                    ->schema([
                                        TextInput::make('meta_title'),
                                        TextInput::make('meta_description'),
                                        TextInput::make('meta_keywords'),
                                    ]),
                                Tab::make('Tax')
                                    ->schema([
                                        TextInput::make('sales_tax_percent')
                                            ->numeric()
                                            ->label('Sales Tax %'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
