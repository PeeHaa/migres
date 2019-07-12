<?php declare(strict_types=1);

namespace PeeHaa\Migres;

use PeeHaa\Migres\Migration\MigrationActions;

final class Migration
{
    private string $name;

    private string $filename;

    private string $fullyQualifiedName;

    private \DateTimeImmutable $timestamp;

    private MigrationActions $actions;

    public function __construct(
        string $name,
        string $filename,
        string $fullyQualifiedName,
        \DateTimeImmutable $timestamp,
        MigrationActions $actions
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

    public function getActions(): MigrationActions
    {
        return $this->actions;
    }

    public function getReverseActions(): MigrationActions
    {
        return $this->actions->reverse();
    }
}
