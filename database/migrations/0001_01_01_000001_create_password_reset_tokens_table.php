<?php

use Luany\Database\Migration\Migration;
use Luany\Framework\Auth\DatabaseTokenRepository;
use Luany\Pt\Esquema;

/**
 * Tokens de recuperação de palavra-passe (só o SHA-256 de cada token).
 */
return new class extends Migration
{
    public function up(\PDO $pdo): void
    {
        $pdo->exec(DatabaseTokenRepository::createTableSql());
    }

    public function down(\PDO $pdo): void
    {
        Esquema::para($pdo)->apagarSeExistir('password_reset_tokens');
    }
};
