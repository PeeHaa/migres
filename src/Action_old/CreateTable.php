<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action_old;

use PeeHaa\Migres\Migration\Queries;

final class CreateTable implements Action
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function toQueries(string $tableName): Queries
    {
        return new Queries(sprintf('CREATE TABLE "%s" ()', $this->name));
    }
}
