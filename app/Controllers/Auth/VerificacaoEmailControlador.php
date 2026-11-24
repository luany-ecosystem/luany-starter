<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\Utilizador;
use Luany\Core\Http\Request;
use Luany\Core\Http\Response;

/**
 * Verificação do email: aviso, link assinado e reenvio.
 */
class VerificacaoEmailControlador extends Controller
{
    public function aviso(): string|Response
    {
        $utilizador = auth()->user();
        if ($utilizador instanceof Utilizador && $utilizador->emailVerificado()) {
            return redirecionar('/painel');
        }

        return vista('auth.verificar-aviso', ['utilizador' => $utilizador]);
    }

    /** GET /email/verificar?id=…&hash=…&expires=…&signature=… (protegido por ValidateSignature) */
    public function verificar(Request $requisicao): Response
    {
        $id         = $requisicao->query('id');
        $utilizador = is_string($id) && ctype_digit($id) ? Utilizador::encontrar((int) $id) : null;

        if ($utilizador === null || !app('auth.verification')->verify($requisicao, $utilizador->chavePrimaria(), $utilizador->email)) {
            abortar(403, traduzir('verificar.invalido'));
        }

        if (!$utilizador->emailVerificado()) {
            // email_verified_at não é preenchível por formulário: só este link o define.
            $utilizador->forceFill(['email_verified_at' => date('Y-m-d H:i:s')]);
            $utilizador->guardar();
        }
        flash('success', traduzir('verificar.feito'));

        return redirecionar(auth()->check() ? '/painel' : '/entrar');
    }

    public function reenviar(): Response
    {
        $utilizador = auth()->user();
        if ($utilizador instanceof Utilizador && !$utilizador->emailVerificado()) {
            self::enviarLink($utilizador);
        }
        flash('success', traduzir('verificar.reenviado'));

        return redirecionar('/email/verificar-aviso');
    }

    public static function enviarLink(Utilizador $utilizador): void
    {
        $url = url(app('auth.verification')->url($utilizador->chavePrimaria(), $utilizador->email));

        mailer()->to($utilizador->email, $utilizador->nome)
            ->subject(traduzir('email.verificar.assunto'))
            ->send('emails.verificar-email', ['nome' => $utilizador->nome, 'url' => $url]);
    }
}
