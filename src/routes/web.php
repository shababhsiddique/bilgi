<?php

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $tags = collect(explode(',', (string) $request->query('tag', '')))
        ->map(fn (string $tag): string => trim($tag))
        ->filter()
        ->values();

    $limit = max(1, (int) $request->query('limit', 100));
    $cursor = (int) $request->query('cursor', 0);

    $images = Image::query()
        ->with('tags:uniqid,name')
        ->where('visible', true)
        ->when($tags->isNotEmpty(), fn ($query) => $query->whereHas(
            'tags',
            fn ($tagQuery) => $tagQuery->whereIn('name', $tags->all()),
        ))
        ->when($cursor > 0, fn ($query) => $query->where('uniqid', '<', $cursor))
        ->orderByDesc('uniqid')
        ->limit($limit + 1)
        ->get(['uniqid', 'url']);

    $hasMore = $images->count() > $limit;
    $page = $images->take($limit)->values();

    return response()->json([
        'data' => $page
        ->map(fn (Image $image): array => [
            'url' => $image->url,
            'tags' => $image->tags->pluck('name')->implode(','),
        ])
        ->values(),
        'next_cursor' => $hasMore ? $page->last()?->uniqid : null,
    ], 200, [], JSON_UNESCAPED_SLASHES);
});
