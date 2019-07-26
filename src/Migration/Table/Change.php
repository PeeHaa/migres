<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration\Table;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddConstraint;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\RemoveColumn;
use PeeHaa\Migres\Action\RemoveConstraint;
use PeeHaa\Migres\Action\RemoveIndex;
use PeeHaa\Migres\Column;
use PeeHaa\Migres\Exception\IrreversibleAction;
use PeeHaa\Migres\Migration\Actions;
use PeeHaa\Migres\Migration\Migrations;

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

        $actions = new Actions($this->tableName);

        foreach ($reversedActions as $action) {
            $actions->add($this->getReversedAction($action, $migrations));
        }

        return $actions;
    }

    private function getReversedAction(Action $action, Migrations $migrations): Action
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
            return new AddColumn($this->findColumnBeforeDeletion($action, $migrations));
        }

        //if ($action instanceof RenameColumn) {
        //    return new RenameColumn($action->getNewName(), $action->getOldName());
        //}
var_dump($action);
        throw new IrreversibleAction(get_class($action));
    }

    private function findColumnBeforeDeletion(RemoveColumn $action, Migrations $migrations): Column
    {
        $column = $migrations->findColumnStateBeforeAction($action, $this->tableName);

        if ($column === null) {
            throw new \Exception('Could not revert column removal as there is no addColumn or changeColumn migration before it.');
        }

        return $column;

        // find last createColumn or changeColumn, renameColumn call



        // find all: addCheck, removeCheck, addIndex, removeIndex, addPrimaryKey, removePrimaryKey
    }
}