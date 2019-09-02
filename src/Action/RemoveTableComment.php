<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;

final class RemoveTableComment extends TableAction implements Action
{
    public function toQueries(): Queries
    {
        return new Queries(sprintf('COMMENT ON TABLE "%s" IS NULL', $this->tableName->toString()));
    }
}
