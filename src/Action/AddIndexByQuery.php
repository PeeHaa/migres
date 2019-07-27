<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class AddIndexByQuery implements Action
{
    private string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries($this->query);
    }
}
