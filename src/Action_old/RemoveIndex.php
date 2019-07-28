<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Migration\Queries;

final class RemoveIndex implements Action
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('DROP INDEX "%s"', $this->name));
    }
}
