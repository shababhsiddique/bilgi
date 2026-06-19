<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Search indexing gate (pre-launch)
    |--------------------------------------------------------------------------
    |
    | The site is gated from search engines until launch. While false, every
    | public page renders `noindex, nofollow` and robots.txt disallows the
    | whole site. Flip SEO_INDEXABLE=true (and clear config cache) at launch —
    | that single switch makes the public shop indexable.
    |
    */

    'indexable' => env('SEO_INDEXABLE', false),

    /*
    |--------------------------------------------------------------------------
    | Production URL
    |--------------------------------------------------------------------------
    |
    | Absolute base used for sitemap entries and the robots.txt Sitemap line.
    | In production this should match APP_URL (https://withbilgi.com).
    |
    */

    'production_url' => rtrim(env('SEO_PRODUCTION_URL', env('APP_URL', 'https://withbilgi.com')), '/'),

    /*
    |--------------------------------------------------------------------------
    | Bangladesh market details
    |--------------------------------------------------------------------------
    |
    | Bilgi serves the Bangladesh market only. These feed Organization /
    | contactPoint structured data on the home page.
    |
    */

    'contact_phone' => env('SEO_CONTACT_PHONE', ''),

    // NOTE: Social profile links for Organization `sameAs` are NOT defined here.
    // They share a single source of truth with the footer — config/social.php.
    // The home page builds `sameAs` from config('social') so the storefront and
    // structured data can never drift apart.

];
