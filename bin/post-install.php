<?php

/**
 * Corre depois de `composer create-project luany/starter`:
 * cria o .env com uma APP_KEY nova e o ficheiro da base SQLite.
 */

if (!file_exists('.env')) {
    if (!file_exists('.env.example')) {
        fwrite(STDERR, "\n  \033[31m✗\033[0m  .env.example não encontrado. Não foi possível criar o .env.\n\n");
        exit(1);
    }
    copy('.env.example', '.env');
}

$env = (string) file_get_contents('.env');
$env = (string) preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=base64:' . base64_encode(random_bytes(32)), $env);
file_put_contents('.env', $env);

$usesSqlite = preg_match('/^DB_CONNECTION=sqlite\s*$/m', $env) === 1;
if ($usesSqlite && preg_match('/^DB_NAME=(.+)$/m', $env, $m) === 1) {
    $file = trim($m[1], " \t\"'");
    if ($file !== ':memory:' && !file_exists($file)) {
        @mkdir(dirname($file), 0755, true);
        touch($file);
    }
}

echo "\n  \033[32m✓\033[0m  .env criado com uma APP_KEY nova\n";
if ($usesSqlite) {
    echo "  \033[32m✓\033[0m  Base SQLite pronta (mude DB_CONNECTION no .env para MySQL / PostgreSQL)\n";
}

echo "\n  A seguir:\n";
echo "    luany migrar      criar as tabelas (luany/cli 1.13+; em versões antigas: luany migrate)\n";
echo "    luany dev         arrancar o servidor com live reload  →  http://localhost:8000\n\n";
