<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class RemoveColumn implements Action
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" DROP COLUMN "%s"', $tableName, $this->name));
    }
}
