<?php

namespace App\Filament\Resources\Images\Pages;

use App\Filament\Resources\Images\ImageResource;
use App\Models\Tag;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;

    protected array $tagNames = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tags'] = $this->record->tags()->pluck('name')->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->tagNames = $this->normalizeTags($data['tags'] ?? []);
        unset($data['tags']);

        return $data;
    }

    protected function afterSave(): void
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
