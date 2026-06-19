<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Public category landing page — an indexable SEO surface that reuses the
     * shop grid (filtered to this category via the Shop\Index Livewire mount).
     */
    public function show(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('visible', true)
            ->firstOrFail();

        return view('pages.category', ['category' => $category]);
    }
}
