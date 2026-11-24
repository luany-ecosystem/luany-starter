<?php

namespace Tests\Feature;

use Luany\Core\Http\Request;
use Luany\Core\Http\Response;
use Luany\Database\Migration\MigrationRunner;
use Luany\Framework\Application;
use Luany\Framework\Http\Kernel;
use Luany\Framework\Mail\Message;
use Luany\Framework\Mail\Transport\ArrayTransport;
use Luany\Framework\Support\AfterResponse;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\Support\ArraySession;

/**
 * Every request runs in a fresh application, as under PHP-FPM: the
 * database (a temporary SQLite file) and the session survive between
 * requests, nothing else does. Work deferred until after the response
 * (password reset emails) runs at the end of each request.
 */
abstract class TestCase extends BaseTestCase
{
    protected ArraySession $session;
    private string $database;

    /** @var list<Message> */
    private array $sent = [];

    protected function setUp(): void
    {
        foreach (glob(dirname(__DIR__, 2) . '/storage/rate-limits/*') ?: [] as $file) {
            @unlink($file);
        }

        $this->database = sys_get_temp_dir() . '/luany-starter-' . bin2hex(random_bytes(4)) . '.sqlite';
        touch($this->database);
        foreach (['DB_NAME' => $this->database] as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $_SERVER[$key] = $value;
        }

        // Static state from the previous test points at its (deleted) database.
        \Luany\Framework\Validation\Validator::setPresenceVerifier(null);

        $this->session = new ArraySession();
        $app = $this->boot();
        (new MigrationRunner($app->make('db'), dirname(__DIR__, 2) . '/database/migrations'))->run();
    }

    protected function tearDown(): void
    {
        @unlink($this->database);
    }

    private function boot(): Application
    {
        /** @var Application $app */
        $app = require dirname(__DIR__, 2) . '/bootstrap/app.php';
        $app->instance('session', $this->session);
        $app->make(Kernel::class)->boot();

        return $app;
    }

    protected function get(string $uri): Response
    {
        $path = (string) parse_url($uri, PHP_URL_PATH);
        parse_str((string) parse_url($uri, PHP_URL_QUERY), $query);

        return $this->send(new Request('GET', $path, $query, [], [], [], ['REMOTE_ADDR' => '127.0.0.1']));
    }

    /** @param array<string, mixed> $body */
    protected function post(string $uri, array $body = []): Response
    {
        $body['_token'] ??= (string) $this->session->get('_csrf_token', '');

        return $this->send(new Request('POST', $uri, [], $body, [], [], ['REMOTE_ADDR' => '127.0.0.1']));
    }

    private function send(Request $request): Response
    {
        $this->session->nextRequest();
        $app = $this->boot();
        if ($request->method() === 'POST' && $request->input('_token') === '') {
            $request = new Request('POST', $request->uri(), [], ['_token' => $app->make('csrf')->token()] + $request->body(), [], [], ['REMOTE_ADDR' => '127.0.0.1']);
        }

        $response = $app->make(Kernel::class)->handle($request);
        AfterResponse::flush();

        $transport = $app->make('mailer')->transport();
        if ($transport instanceof ArrayTransport) {
            array_push($this->sent, ...$transport->messages);
        }

        return $response;
    }

    protected function assertRedirect(Response $response, string $to, string $message = ''): void
    {
        $this->assertContains($response->getStatusCode(), [301, 302, 303], $message . "\n" . $response->getBody());
        $this->assertSame($to, $response->getHeaders()['Location'] ?? null, $message);
    }

    /** @return list<Message> */
    protected function emails(): array
    {
        return $this->sent;
    }

    /** The URL inside the last email (path and query). */
    protected function linkInLastEmail(): string
    {
        $this->assertNotEmpty($this->sent, 'no email was sent');
        preg_match('#https?://[^/\s"]+(/[^\s")<]+)#', html_entity_decode(end($this->sent)->html), $m);
        $this->assertNotEmpty($m, 'no link in the email');

        return $m[1];
    }

    /** @param array<string, string> $data */
    protected function register(array $data = []): void
    {
        $this->post('/registo', $data + [
            'nome' => 'Ana Gola', 'email' => 'ana@exemplo.ao', 'password' => 'segredo123', 'password_confirmation' => 'segredo123',
        ]);
    }
}
