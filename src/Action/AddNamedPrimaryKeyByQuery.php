<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class AddNamedPrimaryKeyByQuery extends TableAction implements Action
{
    private Label $name;

    private string $query;

    public function __construct(Label $tableName, Label $name, string $query)
    {
        parent::__construct($tableName);

        $this->name  = $name;
        $this->query = $query;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf(
            'ALTER TABLE "%s" ADD CONSTRAINT "%s" %s',
            $this->tableName->toString(),
            $this->name->toString(),
            $this->query,
        ));
    }
}
