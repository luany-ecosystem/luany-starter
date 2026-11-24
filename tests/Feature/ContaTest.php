<?php

namespace Tests\Feature;

use App\Models\Utilizador;

class ContaTest extends TestCase
{
    public function test_home_page(): void
    {
        $response = $this->get('/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('já tem contas.', $response->getBody());
        $this->assertStringContainsString('lang="pt-AO"', $response->getBody());
        $this->assertStringContainsString('href="/registo"', $response->getBody());
    }

    public function test_registration_sends_the_verification_email_and_the_link_verifies(): void
    {
        $this->register();

        $utilizador = Utilizador::primeiroOnde('email = ?', ['ana@exemplo.ao']);
        $this->assertNotNull($utilizador);
        $this->assertNotSame('segredo123', $utilizador->getAttribute('password'), 'password is hashed');
        $this->assertTrue(auth()->check());

        // Not verified yet: the panel sends to the notice.
        $this->assertRedirect($this->get('/painel'), '/email/verificar-aviso');
        $this->assertStringContainsString('ana@exemplo.ao', $this->get('/email/verificar-aviso')->getBody());

        $this->assertSame('Confirme o seu email', $this->emails()[0]->subject);
        $link = $this->linkInLastEmail();
        $this->assertStringStartsWith('/email/verificar?', $link);

        $response = $this->get($link);
        $this->assertSame('no-referrer', $response->getHeaders()['Referrer-Policy'] ?? null);
        $this->assertRedirect($response, '/painel');

        $painel = $this->get('/painel');
        $this->assertSame(200, $painel->getStatusCode());
        $this->assertStringContainsString('Olá, Ana Gola', $painel->getBody());
        $this->assertMatchesRegularExpression('/\d{1,2} de [a-zç]+ de \d{4}/u', $painel->getBody(), 'data() in Portuguese');
    }

    public function test_tampered_verification_link_is_rejected(): void
    {
        $this->register();
        $link = $this->linkInLastEmail();

        $this->assertSame(403, $this->get(str_replace('id=1', 'id=2', $link))->getStatusCode());
        $this->assertSame(403, $this->get($link . 'x')->getStatusCode());
    }

    public function test_validation_messages_are_in_portuguese(): void
    {
        $response = $this->post('/registo', ['nome' => '', 'email' => 'não-é-email', 'password' => 'curta', 'password_confirmation' => 'outra']);
        $this->assertRedirect($response, '/registo');

        $page = $this->get('/registo')->getBody();
        $this->assertStringContainsString('O campo nome é obrigatório.', $page);
        $this->assertStringContainsString('O campo email tem de ser um email válido.', $page);
        $this->assertStringContainsString('O campo password tem de ter pelo menos 8 caracteres.', $page);
        $this->assertStringContainsString('value="não-é-email"', $page, 'old input kept');
    }

    public function test_email_is_unique(): void
    {
        $this->register();
        $this->post('/sair');
        $this->register(['nome' => 'Outra']);

        $this->assertStringContainsString('O valor de email já está registado.', $this->get('/registo')->getBody());
    }

    public function test_login_and_logout(): void
    {
        $this->register();
        $this->post('/sair');
        $this->assertFalse(auth()->check());
        $this->assertRedirect($this->get('/painel'), '/entrar');

        $this->post('/entrar', ['email' => 'ana@exemplo.ao', 'password' => 'errada']);
        $this->assertFalse(auth()->check());
        $this->assertStringContainsString('O email ou a palavra-passe não estão corretos.', $this->get('/entrar')->getBody());

        $response = $this->post('/entrar', ['email' => 'ana@exemplo.ao', 'password' => 'segredo123']);
        $this->assertTrue(auth()->check());
        $this->assertRedirect($response, '/painel', 'intended page');

        $this->assertRedirect($this->get('/entrar'), '/painel', 'logged-in users skip the login page');
    }

    public function test_password_reset(): void
    {
        $this->register();
        $this->post('/sair');

        $response = $this->post('/recuperar-palavra-passe', ['email' => 'ana@exemplo.ao']);
        $this->assertRedirect($response, '/recuperar-palavra-passe');
        $sameAnswer = $this->get('/recuperar-palavra-passe')->getBody();
        $this->assertStringContainsString('Se o email estiver registado', $sameAnswer);

        $link = $this->linkInLastEmail();
        $this->assertStringStartsWith('/redefinir-palavra-passe?token=', $link);
        $this->assertStringContainsString('name="token"', $this->get($link)->getBody());

        parse_str((string) parse_url($link, PHP_URL_QUERY), $query);
        $done = $this->post('/redefinir-palavra-passe', [
            'token' => $query['token'], 'email' => $query['email'], 'password' => 'nova-senha-1', 'password_confirmation' => 'nova-senha-1',
        ]);
        $this->assertRedirect($done, '/entrar');

        $this->post('/entrar', ['email' => 'ana@exemplo.ao', 'password' => 'nova-senha-1']);
        $this->assertTrue(auth()->check());
        $this->post('/sair');

        // Single use.
        $again = $this->post('/redefinir-palavra-passe', [
            'token' => $query['token'], 'email' => $query['email'], 'password' => 'outra-senha-2', 'password_confirmation' => 'outra-senha-2',
        ]);
        $this->assertRedirect($again, '/recuperar-palavra-passe');
    }

    public function test_unknown_email_gets_the_same_message_and_no_email(): void
    {
        $this->post('/recuperar-palavra-passe', ['email' => 'ninguem@exemplo.ao']);

        $this->assertStringContainsString('Se o email estiver registado', $this->get('/recuperar-palavra-passe')->getBody());
        $this->assertSame([], $this->emails());
    }

    public function test_login_attempts_are_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/entrar', ['email' => 'x@exemplo.ao', 'password' => 'errada']);
        }

        $this->assertSame(429, $this->post('/entrar', ['email' => 'x@exemplo.ao', 'password' => 'errada'])->getStatusCode());
    }
}
