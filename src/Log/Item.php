<?php declare(strict_types=1);

namespace PeeHaa\Migres\Log;

use PeeHaa\Migres\Migration\Actions;

final class Item
{
    private string $id;

    private string $name;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $executedAt;

    private Actions $rollbackActions = [];

    public function __construct(
        string $id,
        string $name,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $executedAt,
        Actions $rollbackActions
    ) {
        $this->id              = $id;
        $this->name            = $name;
        $this->createdAt       = $createdAt;
        $this->executedAt      = $executedAt;
        $this->rollbackActions = $rollbackActions;
    }

    /**
     * @param array<string,mixed> $record
     */
    public function fromRecord(array $record): self
    {
        return new self(
            $record['id'],
            $record['name'],
            new \DateTimeImmutable($record['created_at']),
            new \DateTimeImmutable($record['executed_at']),
            unserialize($record['rollback_actions']),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getExecutedAt(): \DateTimeImmutable
    {
        return $this->executedAt;
    }

    public function getRollbackActions(): Actions
    {
        return $this->rollbackActions;
    }
}
