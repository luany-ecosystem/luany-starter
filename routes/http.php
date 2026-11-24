<?php

use App\Controllers\Auth\PalavraPasseControlador;
use App\Controllers\Auth\RegistoControlador;
use App\Controllers\Auth\SessaoControlador;
use App\Controllers\Auth\VerificacaoEmailControlador;
use App\Controllers\InicioControlador;
use App\Controllers\PainelControlador;
use App\Http\Middleware\LimitarTentativas;
use Luany\Framework\Http\Middleware\Authenticate;
use Luany\Framework\Http\Middleware\EnsureEmailIsVerified;
use Luany\Framework\Http\Middleware\RedirectIfAuthenticated;
use Luany\Framework\Http\Middleware\ValidateSignature;
use Luany\Pt\Rota;

Rota::get('/', [InicioControlador::class, 'index'])->nome('inicio');

// ── Visitantes ────────────────────────────────────────────────────────────────
Rota::middleware(RedirectIfAuthenticated::class)->grupo(function () {
    Rota::get('/entrar', [SessaoControlador::class, 'mostrar'])->nome('entrar');
    Rota::post('/entrar', [SessaoControlador::class, 'entrar'])->middleware(LimitarTentativas::class);

    Rota::get('/registo', [RegistoControlador::class, 'mostrar'])->nome('registo');
    Rota::post('/registo', [RegistoControlador::class, 'registar'])->middleware(LimitarTentativas::class);

    Rota::get('/recuperar-palavra-passe', [PalavraPasseControlador::class, 'pedir'])->nome('palavra-passe.pedir');
    Rota::post('/recuperar-palavra-passe', [PalavraPasseControlador::class, 'enviar'])->middleware(LimitarTentativas::class);

    Rota::get('/redefinir-palavra-passe', [PalavraPasseControlador::class, 'mostrarRedefinir'])->nome('palavra-passe.redefinir');
    Rota::post('/redefinir-palavra-passe', [PalavraPasseControlador::class, 'redefinir'])->middleware(LimitarTentativas::class);
});

// ── Verificação do email (o link funciona com ou sem sessão) ──────────────────
Rota::get('/email/verificar', [VerificacaoEmailControlador::class, 'verificar'])
    ->nome('email.verificar')
    ->middleware(ValidateSignature::class);

// ── Com sessão ────────────────────────────────────────────────────────────────
Rota::middleware(Authenticate::class)->grupo(function () {
    Rota::post('/sair', [SessaoControlador::class, 'sair'])->nome('sair');

    Rota::get('/email/verificar-aviso', [VerificacaoEmailControlador::class, 'aviso'])->nome('email.aviso');
    Rota::post('/email/reenviar', [VerificacaoEmailControlador::class, 'reenviar'])
        ->nome('email.reenviar')
        ->middleware(LimitarTentativas::class);

    Rota::middleware(EnsureEmailIsVerified::class)->grupo(function () {
        Rota::get('/painel', [PainelControlador::class, 'index'])->nome('painel');
    });
});
