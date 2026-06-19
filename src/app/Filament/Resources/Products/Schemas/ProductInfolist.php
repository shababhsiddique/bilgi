<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('thumbnail')
                    ->label('Thumbnail')
                    ->visible(fn (Product $record): bool => filled($record->thumbnail))
                    ->disk('public')
                    ->height(200)
                    ->width(200)
                    ->columnSpanFull(),

                TextEntry::make('product_type')
                    ->badge(),
                TextEntry::make('age_group')
                    ->badge(),
                IconEntry::make('track_inventory')
                    ->boolean(),
                /*TextEntry::make('cost')
                    ->money(),
                TextEntry::make('sales_price')
                    ->money(),*/
                TextEntry::make('sales_tax_percent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                /*TextEntry::make('barcode')
                    ->placeholder('-'),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),*/
                IconEntry::make('visible')
                    ->boolean(),
                TextEntry::make('ribbon_text')
                    ->placeholder('-'),
                TextEntry::make('thumbnail')
                    ->placeholder('-'),
                /*TextEntry::make('weight')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('volume')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('min_stock')
                    ->numeric(),*/
                IconEntry::make('is_digital')
                    ->boolean(),
                TextEntry::make('slug'),
                TextEntry::make('brand')
                    ->placeholder('-'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('meta_title')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->placeholder('-'),
                TextEntry::make('meta_keywords')
                    ->placeholder('-'),
                ImageEntry::make('og_image')
                    ->label('Social Share Image')
                    ->visible(fn (Product $record): bool => filled($record->og_image))
                    ->disk('public')
                    ->height(120)
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
