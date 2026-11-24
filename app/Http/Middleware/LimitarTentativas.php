<?php

namespace App\Http\Middleware;

use Luany\Core\Http\Request;
use Luany\Core\Middleware\RateLimitMiddleware;
use Luany\Core\RateLimit\FileRateLimiter;

/**
 * Limite de pedidos por IP e por rota nos formulários de conta (entrar,
 * registo, recuperar a palavra-passe, reenviar a verificação): por omissão
 * 5 por minuto, configurável em config/auth.php → throttle (AUTH_THROTTLE_MAX,
 * AUTH_THROTTLE_SECONDS). O IP real por trás de um proxy só conta se o
 * proxy estiver em TRUSTED_PROXIES.
 */
class LimitarTentativas extends RateLimitMiddleware
{
    public function __construct()
    {
        parent::__construct(
            new FileRateLimiter(app()->storagePath('rate-limits')),
            max(1, (int) configuracao('auth.throttle.max', 5)),
            max(1, (int) configuracao('auth.throttle.seconds', 60)),
            'tentativas'
        );
    }

    protected function keyFor(Request $request): string
    {
        return 'tentativas:' . $request->uri() . ':' . $request->ip();
    }
}
