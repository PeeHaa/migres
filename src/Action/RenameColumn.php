<?php declare(strict_types=1);

namespace PeeHaa\Migres\Action;

use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Specification\Label;

final class RenameColumn extends TableAction implements Action
{
    private Label $oldName;

    private Label $newName;

    public function __construct(Label $tableName, Label $oldName, Label $newName)
    {
        parent::__construct($tableName);

        $this->oldName = $oldName;
        $this->newName = $newName;
    }

    public function getOldName(): Label
    {
        return $this->oldName;
    }

    public function getNewName(): Label
    {
        return $this->newName;
    }

    public function toQueries(): Queries
    {
        return new Queries(
            sprintf(
                'ALTER TABLE "%s" RENAME COLUMN "%s" TO "%s"',
                $this->tableName->toString(),
                $this->oldName->toString(),
                $this->newName->toString(),
            ),
        );
    }
}
