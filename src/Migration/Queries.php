<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration;

final class Queries implements \Iterator
{
    /** @var array<string> */
    private array $queries = [];

    public function __construct(string ...$queries)
    {
        $this->queries = $queries;
    }

    public function current(): string
    {
        return current($this->queries);
    }

    public function next(): void
    {
        next($this->queries);
    }

    public function key(): ?int
    {
        return key($this->queries);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->queries);
    }
}
