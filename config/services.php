<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'oauth' => 2,
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/socialLogin/auth/google/callback',
        'map_key' => env('GOOGLE_MAP_KEY', 'AIzaSyCyI7RqHvfZqRjzW-kdPpNHn5w_MGG0WlE'),
    ],

    'facebook' => [
        'oauth' => 2,
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') .'/socialLogin/auth/facebook/callback',
    ],

    'twitter' => [
        'oauth' => 1,
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/auth/callback',
    ],

    'apple' => [
        'oauth' => 2,
        'client_id' => explode(',', env('APPLE_CLIENT_IDS', 'net.eshhaar.app,net.eshhaar.apple')),
        'redirect'      => env('APP_URL') . '/auth/callback',
    ],

];
