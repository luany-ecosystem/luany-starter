<?php

use Luany\Database\Migration\Migration;
use Luany\Pt\Esquema;
use Luany\Pt\Tabela;

return new class extends Migration
{
    public function up(\PDO $pdo): void
    {
        Esquema::para($pdo)->criar('utilizadores', function (Tabela $t) {
            $t->id();
            $t->texto('nome', 120);
            $t->texto('email', 150)->unico();
            $t->texto('password');
            $t->dataHora('email_verified_at')->anulavel();
            $t->marcasTempo();
        });
    }

    public function down(\PDO $pdo): void
    {
        Esquema::para($pdo)->apagarSeExistir('utilizadores');
    }
};
