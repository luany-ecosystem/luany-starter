<?php

namespace App\Controllers;

class InicioControlador extends Controller
{
    public function index(): string
    {
        return vista('pages.inicio');
    }
}
