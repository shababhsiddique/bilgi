<?php

namespace App\Filament\Resources\Recipees\Pages;

use App\Filament\Resources\Recipees\RecipeeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRecipee extends EditRecord
{
    protected static string $resource = RecipeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
