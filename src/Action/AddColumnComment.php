<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class AddColumnComment extends TableAction implements Action
{
    private Label $columnName;

    private string $comment;

    public function __construct(Label $tableName, Label $columnName, string $comment)
    {
        parent::__construct($tableName);

        $this->columnName = $columnName;
        $this->comment    = $comment;
    }

    public function toQueries(): Queries
    {
        return new Queries(sprintf(
            'COMMENT ON COLUMN "%s"."%s" IS \'%s\'',
            $this->tableName->toString(),
            $this->columnName->toString(),
            $this->comment,
        ));
    }
}
