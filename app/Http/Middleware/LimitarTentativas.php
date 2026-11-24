<?php

namespace App\Http\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Middleware\RateLimitMiddleware;
use Luany\Core\RateLimit\FileRateLimiter;

/**
 * No máximo 5 pedidos por minuto, por IP e por rota (entrar, registo,
 * recuperar a palavra-passe, reenviar a verificação). O IP real por trás de
 * um proxy só conta se o proxy estiver em TRUSTED_PROXIES.
 */
class LimitarTentativas extends RateLimitMiddleware
{
    public function __construct()
    {
        parent::__construct(new FileRateLimiter(app()->storagePath('rate-limits')), 5, 60, 'tentativas');
    }

    protected function keyFor(Request $request): string
    {
        return 'tentativas:' . $request->uri() . ':' . $request->ip();
    }
}
