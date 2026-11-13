<?php

namespace Tests;

/** Array-backed stand-in for the Session facade's store. */
class FakeSession
{
    public function __construct(private array $data = []) {}

    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function exists($key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function has($key): bool
    {
        return isset($this->data[$key]);
    }
}
