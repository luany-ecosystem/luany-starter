<?php

namespace App\Models;

use Luany\Pt\Modelo;

/**
 * Quem entra na aplicação. As colunas `password` e `email_verified_at`
 * mantêm o nome inglês: é assim que o luany/framework as lê
 * (auth(), recuperação de palavra-passe, EnsureEmailIsVerified).
 */
class Utilizador extends Modelo
{
    protected string $tabela = 'utilizadores';

    protected ?bool $datasAutomaticas = true;

    protected array $preenchiveis = ['nome', 'email', 'password'];

    protected array $ocultos = ['password'];

    protected array $conversoes = [
        'email_verified_at' => 'datetime',
    ];

    public function emailVerificado(): bool
    {
        return $this->email_verified_at !== null;
    }
}
