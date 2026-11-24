<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Framework\Auth\PasswordBroker;

/**
 * Recuperar e redefinir a palavra-passe.
 */
class PalavraPasseControlador extends Controller
{
    public function pedir(): string
    {
        return vista('auth.recuperar');
    }

    public function enviar(Request $requisicao): Response
    {
        $dados = validar($requisicao->body(), ['email' => 'obrigatorio|email|max:150'], '/recuperar-palavra-passe');
        $email = strtolower($dados['email']);

        $estado = app('auth.passwords')->sendResetLink(['email' => $email], $requisicao->ip(),
            function (object $utilizador, string $token) use ($email): void {
                $url = url('/redefinir-palavra-passe?token=' . $token . '&email=' . rawurlencode($email));

                mailer()->to($email, (string) $utilizador->nome)
                    ->subject(traduzir('email.redefinir.assunto'))
                    ->send('emails.redefinir-palavra-passe', ['nome' => $utilizador->nome, 'url' => $url]);
            });

        // A mesma mensagem para qualquer email: não revela que contas existem.
        flash($estado === PasswordBroker::THROTTLED ? 'warning' : 'success', $estado === PasswordBroker::THROTTLED
            ? traduzir('recuperar.limite')
            : traduzir('recuperar.enviado'));

        return redirecionar('/recuperar-palavra-passe');
    }

    public function mostrarRedefinir(Request $requisicao): string
    {
        return vista('auth.redefinir', [
            'token' => (string) $requisicao->query('token', ''),
            'email' => (string) $requisicao->query('email', ''),
        ]);
    }

    public function redefinir(Request $requisicao): Response
    {
        $dados = validar($requisicao->body(), [
            'token'    => 'obrigatorio|texto',
            'email'    => 'obrigatorio|email',
            'password' => 'obrigatorio|texto|min:8|max:255|confirmado',
        ], '/redefinir-palavra-passe?token=' . rawurlencode((string) $requisicao->input('token', '')) . '&email=' . rawurlencode((string) $requisicao->input('email', '')));

        $estado = app('auth.passwords')->reset([
            'email'    => strtolower($dados['email']),
            'token'    => $dados['token'],
            'password' => $dados['password'],
        ]);

        if ($estado !== PasswordBroker::PASSWORD_RESET) {
            flash('error', traduzir('redefinir.invalido'));

            return redirecionar('/recuperar-palavra-passe');
        }

        flash('success', traduzir('redefinir.feito'));

        return redirecionar('/entrar');
    }
}
