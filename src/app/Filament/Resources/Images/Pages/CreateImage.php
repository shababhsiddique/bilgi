<?php

namespace App\Filament\Resources\Images\Pages;

use App\Filament\Resources\Images\ImageResource;
use App\Models\Tag;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateImage extends CreateRecord
{
    protected static string $resource = ImageResource::class;

    protected array $tagNames = [];

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->color('primary');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->tagNames = $this->normalizeTags($data['tags'] ?? []);
        unset($data['tags']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $tagIds = collect($this->tagNames)
            ->map(fn (string $name): int => (int) Tag::firstOrCreate(['name' => $name])->getKey())
            ->all();

        $this->record->tags()->sync($tagIds);
    }

    /**
     * @param  array<int, string>|string  $tags
     * @return array<int, string>
     */
    protected function normalizeTags(array|string $tags): array
    {
        return collect(is_array($tags) ? $tags : [$tags])
            ->flatMap(fn (string $value): array => preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY) ?: [])
            ->map(fn (string $value): string => trim($value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
