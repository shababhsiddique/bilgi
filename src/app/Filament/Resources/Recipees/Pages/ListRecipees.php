<?php

namespace App\Filament\Resources\Recipees\Pages;

use App\Filament\Resources\Recipees\RecipeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecipees extends ListRecords
{
    protected static string $resource = RecipeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
