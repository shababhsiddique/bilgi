<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Render the XML sitemap.
     *
     * While the site is gated (config('seo.indexable') === false) we still
     * expose the sitemap, but only with the home page, so nothing leaks into
     * search before launch.
     */
    public function index(): Response
    {
        $indexable = config('seo.indexable');

        $urls = [
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ];

        if ($indexable) {
            $urls[] = ['loc' => route('shop'), 'changefreq' => 'daily', 'priority' => '0.9'];
            $urls[] = ['loc' => route('story'), 'changefreq' => 'yearly', 'priority' => '0.4'];

            Category::query()
                ->where('visible', true)
                ->orderBy('name')
                ->get(['slug', 'updated_at'])
                ->each(function (Category $category) use (&$urls) {
                    $urls[] = [
                        'loc'        => route('category.show', $category->slug),
                        'lastmod'    => optional($category->updated_at)->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.7',
                    ];
                });

            Product::query()
                ->where('visible', true)
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->each(function (Product $product) use (&$urls) {
                    $urls[] = [
                        'loc'        => route('product.show', $product->slug),
                        'lastmod'    => optional($product->updated_at)->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.8',
                    ];
                });
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
