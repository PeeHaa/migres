<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class DropTable extends TableAction implements Action
{
    public function toQueries(): Queries
    {
        return new Queries(sprintf('DROP TABLE "%s"', $this->tableName->toString()));
    }
}
