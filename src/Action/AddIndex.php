<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Migration\Queries;

final class AddIndex implements Action
{
    private Index $index;

    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    public function getIndex(): Index
    {
        return $this->index;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries($this->index->toSql());
    }
}
