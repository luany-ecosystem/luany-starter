<?php

return [
    'name'     => env('APP_NAME', 'Luany'),
    'env'      => env('APP_ENV', 'production'),
    'debug'    => (bool) env('APP_DEBUG', false),
    'url'      => env('APP_URL', 'http://localhost:8000'),
    'timezone' => env('APP_TIMEZONE', 'Africa/Luanda'),

    // Traduções em lang/{locale}.php. pt_AO recorre a pt e depois a en.
    'locale'            => env('APP_LOCALE', 'pt_AO'),
    'fallback_locale'   => env('APP_FALLBACK_LOCALE', 'en'),
    'supported_locales' => env('APP_SUPPORTED_LOCALES', 'pt_AO,pt_PT,pt_BR,pt,en'),

    // Vistas com as diretivas em português (@se, @paracada, @secao…).
    'syntax' => env('LUANY_SYNTAX', 'pt'),

    // Mensagens da CLI e diagnósticos em português.
    'dev_language' => env('LUANY_DEV_LANGUAGE', 'pt'),
];
