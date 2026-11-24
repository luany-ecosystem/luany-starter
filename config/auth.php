<?php

// Autenticação — auth(), app('auth.passwords'), app('auth.verification').
return [
    'model'         => App\Models\Utilizador::class,
    'login'         => '/entrar',                // para onde vão os visitantes
    'home'          => '/painel',                // para onde vai quem já tem sessão
    'verify_path'   => '/email/verificar',       // link de verificação enviado por email
    'verify_notice' => '/email/verificar-aviso', // para onde vai quem ainda não verificou o email
];
