<?php declare(strict_types=1);

namespace PeeHaa\Migres;

final class Rollback
{
    private int $id;

    private string $name;

    private string $filename;

    private string $fullyQualifiedName;

    private \DateTimeImmutable $timestamp;

    /** @var array<string> */
    private array $queries;

    public function __construct(
        int $id,
        string $name,
        string $filename,
        string $fullyQualifiedName,
        \DateTimeImmutable $timestamp,
        string ...$queries
    ) {
        $this->id                 = $id;
        $this->name               = $name;
        $this->filename           = $filename;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->timestamp          = $timestamp;
        $this->queries            = $queries;
    }

    /**
     * @param array<string,mixed> $logRecord
     * @throws \Exception
     */
    public static function fromLogRecord(array $logRecord): self
    {
        return new self(
            $logRecord['id'],
            $logRecord['name'],
            $logRecord['filename'],
            $logRecord['fully_qualified_name'],
            new \DateTimeImmutable($logRecord['timestamp']),
            ...json_decode($logRecord['rollback_actions'], true),
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @return array<string>
     */
    public function getQueries(): array
    {
        return $this->queries;
    }
}
