<?php

// Autenticação — auth(), app('auth.passwords'), app('auth.verification').
return [
    'model'         => App\Models\Utilizador::class,
    'login'         => '/entrar',                // para onde vão os visitantes
    'home'          => '/painel',                // para onde vai quem já tem sessão
    'verify_path'   => '/email/verificar',       // link de verificação enviado por email
    'verify_notice' => '/email/verificar-aviso', // para onde vai quem ainda não verificou o email

    // Limite dos formulários de conta (LimitarTentativas), por IP e por rota.
    // Suba-o se muitos utilizadores saem pelo mesmo IP (escritórios, universidades).
    'throttle' => [
        'max'     => (int) env('AUTH_THROTTLE_MAX', 5),
        'seconds' => (int) env('AUTH_THROTTLE_SECONDS', 60),
    ],
];
