<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Migration\Queries;

final class DropTable implements Action
{
    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('DROP TABLE "%s"', $tableName));
    }
}
