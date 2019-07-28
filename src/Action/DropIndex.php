<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class DropIndex extends TableAction implements Action
{
    private string $name;

    public function __construct(string $tableName, string $name)
    {
        parent::__construct($tableName);

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('DROP INDEX "%s"', $this->name));
    }
}
