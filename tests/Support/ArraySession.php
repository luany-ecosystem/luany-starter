<?php

namespace Tests\Support;

use Luany\Framework\Contracts\SessionInterface;

/** Session in memory for tests (PHP's native sessions need a real HTTP request). */
final class ArraySession implements SessionInterface
{
    /** @var array<string, mixed> */
    private array $data = [];
    /** @var array<string, mixed> */
    private array $flashNew = [];
    /** @var array<string, mixed> */
    private array $flashOld = [];

    public function start(): void {}
    public function get(string $key, mixed $default = null): mixed { return $this->data[$key] ?? $this->flashOld[$key] ?? $this->flashNew[$key] ?? $default; }
    public function set(string $key, mixed $value): void { $this->data[$key] = $value; }
    public function has(string $key): bool { return isset($this->data[$key]) || isset($this->flashOld[$key]) || isset($this->flashNew[$key]); }
    public function forget(string $key): void { unset($this->data[$key], $this->flashOld[$key], $this->flashNew[$key]); }
    public function flash(string $key, mixed $value): void { $this->flashNew[$key] = $value; }
    public function regenerate(): void {}
    public function save(): void {}
    public function getId(): string { return 'test'; }
    public function all(): array { return $this->data + $this->flashOld + $this->flashNew; }
    public function destroy(): void { $this->data = $this->flashNew = $this->flashOld = []; }

    /** Next request: this request's flash data becomes readable, the previous one is gone. */
    public function nextRequest(): void
    {
        $this->flashOld = $this->flashNew;
        $this->flashNew = [];
    }
}
