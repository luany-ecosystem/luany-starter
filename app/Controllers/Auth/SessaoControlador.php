<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Luany\Core\Http\Request;
use Luany\Core\Http\Response;

/**
 * Entrar e sair.
 */
class SessaoControlador extends Controller
{
    public function mostrar(): string
    {
        return vista('auth.entrar');
    }

    public function entrar(Request $requisicao): Response
    {
        $dados = validar($requisicao->body(), [
            'email'    => 'obrigatorio|email|max:150',
            'password' => 'obrigatorio|texto',
        ], '/entrar');

        if (!auth()->attempt(['email' => $dados['email'], 'password' => $dados['password']])) {
            sessao()->flash('errors', ['email' => ['O email ou a palavra-passe não estão corretos.']]);
            sessao()->flash('_old_input', ['email' => $dados['email']]);

            return redirecionar('/entrar');
        }

        return redirecionar(auth()->intended('/painel'));
    }

    public function sair(): Response
    {
        auth()->logout();
        flash('success', 'Sessão terminada.');

        return redirecionar('/');
    }
}
