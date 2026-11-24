<?php

namespace App\Controllers;

class PainelControlador extends Controller
{
    public function index(): string
    {
        return vista('pages.painel', ['utilizador' => auth()->user()]);
    }
}
