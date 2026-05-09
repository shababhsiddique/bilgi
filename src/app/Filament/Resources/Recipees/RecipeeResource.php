<?php

namespace App\Filament\Resources\Recipees;

use App\Filament\Resources\Recipees\Pages\CreateRecipee;
use App\Filament\Resources\Recipees\Pages\EditRecipee;
use App\Filament\Resources\Recipees\Pages\ListRecipees;
use App\Filament\Resources\Recipees\Pages\ViewRecipee;
use App\Filament\Resources\Recipees\Schemas\RecipeeForm;
use App\Filament\Resources\Recipees\Schemas\RecipeeInfolist;
use App\Filament\Resources\Recipees\Tables\RecipeesTable;
use App\Models\Recipee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecipeeResource extends Resource
{
    protected static ?string $model = Recipee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RecipeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecipeesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecipeeInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecipees::route('/'),
            'create' => CreateRecipee::route('/create'),
            'view' => ViewRecipee::route('/{record}'),
            'edit' => EditRecipee::route('/{record}/edit'),
        ];
    }
}
