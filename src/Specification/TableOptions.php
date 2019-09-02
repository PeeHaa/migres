<?php declare(strict_types=1);

namespace PeeHaa\Migres\Specification;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddTableComment;
use PeeHaa\Migres\Action\RemoveTableComment;

final class TableOptions
{
    private Label $tableName;

    /** @var AddTableComment|RemoveTableComment|null */
    private ?Action $commentAction = null;

    public function __construct(Label $tableName)
    {
        $this->tableName = $tableName;
    }

    public function comment(string $comment): self
    {
        $this->commentAction = new AddTableComment($this->tableName, $comment);

        return $this;
    }

    public function removeComment(): self
    {
        $this->commentAction = new RemoveTableComment($this->tableName);

        return $this;
    }

    /**
     * @internal
     * @return array<Action>
     */
    public function getActions(): array
    {
        $actions = [];

        if ($this->commentAction !== null) {
            $actions[] = $this->commentAction;
        }

        return $actions;
    }
}
