<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class CreateTable extends TableAction implements Action
{
    public function toQueries(): Queries
    {
        return new Queries(sprintf('CREATE TABLE "%s" ()', $this->tableName));
    }
}
