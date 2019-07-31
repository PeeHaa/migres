<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class AddForeignByQuery extends TableAction implements Action
{
    private string $query;

    public function __construct(string $tableName, string $query)
    {
        parent::__construct($tableName);

        $this->query = $query;
    }

    public function toQueries(): Queries
    {
        return new Queries($this->query);
    }
}
