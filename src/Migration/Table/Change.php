<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration\Table;

use PeeHaa\Migres\Migration\Actions;

final class Change implements Migration
{
    private string $tableName;

    private Actions $actions;

    public function __construct(string $tableName, Actions $actions)
    {
        $this->tableName = $tableName;
        $this->actions   = $actions;
    }

    /**
     * @internal
     */
    public function up(): Actions
    {
        return $this->actions;
    }

    /**
     * @internal
     */
    public function down(): Actions
    {
        $reversedActions = $this->actions->reverse();

        $queries = new Queries();

        foreach ($reversedActions as $action) {
            $queries->merge($this->getReversedAction($action, $reversedActions)->toQueries($this->tableName));
        }

        return $queries;
    }

    private function getReversedAction(Action $action, Queue $actions): Action
    {
        if ($action instanceof AddColumn) {
            return new RemoveColumn($action->getColumn()->getName());
        }

        if ($action instanceof AddIndex) {
            return new RemoveIndex($action->getIndex()->getName());
        }

        if ($action instanceof AddConstraint) {
            return new RemoveConstraint($action->getConstraint()->getName());
        }

        if ($action instanceof AddPrimaryKey) {
            return new RemoveConstraint($action->getCombinedPrimaryKey()->getName());
        }

        if ($action instanceof RemoveColumn) {
            return new AddColumn($this->findColumnBeforeDeletion($action, $actions));
        }

        if ($action instanceof RenameColumn) {
            return new RenameColumn($action->getNewName(), $action->getOldName());
        }

        throw new \Exception('Action could not be reversed.');
    }

    private function findColumnBeforeDeletion(Action $action, Queue $actions): Column
    {
        return $actions->findColumnBeforeAction($action);
    }
}
