<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class RenameTable implements Action
{
    private string $originalName;

    private string $newName;

    public function __construct(string $originalName, string $newName)
    {
        $this->originalName = $originalName;
        $this->newName      = $newName;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" RENAME TO "%s"', $this->originalName, $this->newName));
    }
}
