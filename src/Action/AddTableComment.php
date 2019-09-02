<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class AddTableComment extends TableAction implements Action
{
    private string $comment;

    public function __construct(Label $tableName, string $comment)
    {
        parent::__construct($tableName);

        $this->comment = $comment;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf('COMMENT ON TABLE "%s" IS \'%s\'', $this->tableName->toString(), $this->comment));
    }
}
