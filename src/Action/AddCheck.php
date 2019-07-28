<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Migration\Queries;

final class AddCheck extends TableAction implements Action
{
    private Check $check;

    public function __construct(string $tableName, Check $check)
    {
        parent::__construct($tableName);

        $this->check = $check;
    }

    public function getCheck(): Check
    {
        return $this->check;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('ALTER TABLE "%s" ADD %s', $this->tableName, $this->check->toSql()));
    }
}
