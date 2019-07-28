<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Migration\Queries;

final class RenameColumn implements Action
{
    private string $oldName;

    private string $newName;

    public function __construct(string $oldName, string $newName)
    {
        $this->oldName = $oldName;
        $this->newName = $newName;
    }

    public function getOldName(): string
    {
        return $this->oldName;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(
            sprintf('ALTER TABLE "%s" RENAME COLUMN "%s" TO "%s"', $tableName, $this->oldName, $this->newName),
        );
    }
}
