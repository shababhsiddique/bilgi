<?php

namespace App\Filament\Resources\Recipees\Pages;

use App\Filament\Resources\Recipees\RecipeeResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRecipee extends ViewRecord
{
    protected static string $resource = RecipeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('craft')
                ->label('Craft')
                ->requiresConfirmation()
                ->action(fn () => $this->record->craft())
                ->successNotificationTitle('Craft completed.'),
            EditAction::make(),
        ];
    }
}
