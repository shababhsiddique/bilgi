<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Social / Contact Links
    |--------------------------------------------------------------------------
    |
    | Public profile and contact links used across the storefront (footer,
    | contact section, etc.). Set to null/empty to hide a link.
    |
    */

    'facebook'  => env('SOCIAL_FACEBOOK', 'https://www.facebook.com/createwithbilgi'),
    'instagram' => env('SOCIAL_INSTAGRAM', 'https://www.instagram.com/withbilgi'),

    // WhatsApp: digits only, international format (no '+'), used to build wa.me links.
    'whatsapp'  => env('SOCIAL_WHATSAPP', '8801577656983'),

];
