<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class DropIndex extends TableAction implements Action
{
    private Label $name;

    public function __construct(Label $tableName, Label $name)
    {
        parent::__construct($tableName);

        $this->name = $name;
    }

    public function getName(): Label
    {
        return $this->name;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('DROP INDEX "%s"', $this->name->toString()));
    }
}
