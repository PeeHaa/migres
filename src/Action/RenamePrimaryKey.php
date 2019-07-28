<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class RenamePrimaryKey extends TableAction implements Action
{
    private string $oldName;

    private string $newName;

    public function __construct(string $tableName, string $oldName, string $newName)
    {
        parent::__construct($tableName);

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

    public function toQueries(): Queries
    {
        return new Queries(
            sprintf('ALTER INDEX "%s" RENAME TO "%s"', $this->oldName, $this->newName),
        );
    }
}
