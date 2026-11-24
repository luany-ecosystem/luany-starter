<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\Utilizador;
use Luany\Core\Http\Request;
use Luany\Core\Http\Response;

/**
 * Criar conta: o utilizador fica com sessão iniciada e recebe o email de
 * verificação.
 */
class RegistoControlador extends Controller
{
    public function mostrar(): string
    {
        return vista('auth.registo');
    }

    public function registar(Request $requisicao): Response
    {
        $dados = validar($requisicao->body(), [
            'nome'     => 'obrigatorio|texto|max:120',
            'email'    => 'obrigatorio|email|max:150|unico:utilizadores,email',
            'password' => 'obrigatorio|texto|min:8|max:255|confirmado',
        ], '/registo');

        $utilizador = Utilizador::criar([
            'nome'     => $dados['nome'],
            'email'    => strtolower($dados['email']),
            'password' => app('hash')->make($dados['password']),
        ]);

        auth()->login($utilizador);
        VerificacaoEmailControlador::enviarLink($utilizador);

        return redirecionar('/painel');
    }
}
