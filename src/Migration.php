<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Migration\TableActions;

final class Migration
{
    private string $name;

    private string $filename;

    private string $fullyQualifiedName;

    private \DateTimeImmutable $timestamp;

    /** @var array<TableActions> */
    private array $actions;

    public function __construct(
        string $name,
        string $filename,
        string $fullyQualifiedName,
        \DateTimeImmutable $timestamp,
        TableActions ...$actions
    ) {
        $this->name               = $name;
        $this->filename           = $filename;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->timestamp          = $timestamp;
        $this->actions            = $actions;
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
     * @return array<TableActions>
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}
