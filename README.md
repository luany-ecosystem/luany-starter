# Luany Starter

Ponto de partida para aplicações [Luany](https://docs.luany.dev) **em português**, com contas prontas a usar:

- registo, entrada e saída;
- recuperação de palavra-passe por email;
- verificação do email;
- painel só para contas com o email confirmado;
- mensagens de validação e datas em português (`pt_AO`, com `pt` e `en` de recurso).

O código também é em português: o modelo `Utilizador` (`luany/pt`), controladores com `vista()`, `validar()` e `redirecionar()`, migrações com `Esquema`/`Tabela`, rotas com `Rota` e vistas com as diretivas `@estende`, `@secao`, `@se`…

## Requisitos

- PHP 8.2+ com `pdo_sqlite` (ou `pdo_mysql` / `pdo_pgsql`) e `mbstring`
- Composer
- Node.js — só para o live reload (`luany dev`)

## Criar um projeto

```bash
composer create-project luany/starter minha-app
cd minha-app
luany migrar
luany dev          # http://localhost:8000
```

Em desenvolvimento, os emails (verificação, recuperação de palavra-passe) vão para o log em `storage/logs`, de onde se copiam os links. Para enviar a sério, configure o SMTP no `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=ola@exemplo.ao
MAIL_HOST=smtp.exemplo.ao
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

## O que está onde

```
app/Controllers/Auth/      SessaoControlador, RegistoControlador, PalavraPasseControlador,
                           VerificacaoEmailControlador
app/Controllers/           InicioControlador, PainelControlador
app/Models/Utilizador.php  a conta (tabela utilizadores)
app/Http/Middleware/       LimitarTentativas — 5 pedidos por minuto, por IP e por rota
config/auth.php            modelo e caminhos (/entrar, /painel, /email/verificar…)
database/migrations/       utilizadores, password_reset_tokens
routes/http.php            todas as rotas
views/auth/                entrar, registo, recuperar, redefinir, verificar-aviso
views/emails/              verificar-email, redefinir-palavra-passe
views/pages/               inicio, painel
tests/Feature/ContaTest    os fluxos completos
```

| Rota | Para quê |
|---|---|
| `GET/POST /registo` | criar conta (inicia sessão e envia a verificação) |
| `GET/POST /entrar`, `POST /sair` | entrar e sair |
| `GET/POST /recuperar-palavra-passe` | pedir o link (a resposta é igual exista ou não a conta) |
| `GET/POST /redefinir-palavra-passe` | nova palavra-passe com o token do email |
| `GET /email/verificar` | link assinado do email (expira numa hora) |
| `GET /email/verificar-aviso`, `POST /email/reenviar` | aviso e reenvio |
| `GET /painel` | só com sessão e email confirmado |

As colunas `password` e `email_verified_at` mantêm o nome inglês porque é assim que o luany/framework as lê.

## Segurança incluída

Palavras-passe com Argon2id (ou bcrypt), sessão regenerada no login, tokens de recuperação guardados só como hash, de uso único e com validade de 60 minutos, links de verificação assinados com a `APP_KEY` e ligados ao email atual, limites de tentativas, CSRF em todos os formulários. Atrás de um balanceador ou CDN, defina `TRUSTED_PROXIES` no `.env` para os limites contarem o IP real.

Rodar a `APP_KEY` invalida os links de verificação já enviados.

## Testes

```bash
composer test
```

## Documentação

- [Autenticação](https://docs.luany.dev/pt/docs/authentication)
- [Email](https://docs.luany.dev/pt/docs/mail)
- [Programar em português](https://docs.luany.dev/pt/docs/portuguese)

## Licença

MIT
