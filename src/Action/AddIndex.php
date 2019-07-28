<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Migration\Queries;

final class AddIndex extends TableAction implements Action
{
    private Index $index;

    public function __construct(string $tableName, Index $index)
    {
        parent::__construct($tableName);

        $this->index = $index;
    }

    public function getIndex(): Index
    {
        return $this->index;
    }

    public function toQueries(): Queries
    {
        return new Queries($this->index->toSql());
    }
}
