<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddConstraint;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\RemoveColumn;
use PeeHaa\Migres\Action\RemoveConstraint;
use PeeHaa\Migres\Action\RemoveIndex;
use PeeHaa\Migres\Action\ReverseAction;
use PeeHaa\Migres\Column;
use PeeHaa\Migres\Exception\IrreversibleAction;

final class Retrospector
{
    private \PDO $dbConnection;

    private DataTypeResolver $dataTypeResolver;

    private ColumnOptionsResolver $columnOptionsResolver;

    public function __construct(
        \PDO $dbConnection,
        DataTypeResolver $dataTypeResolver,
        ColumnOptionsResolver $columnOptionsResolver
    ) {
        $this->dbConnection          = $dbConnection;
        $this->dataTypeResolver      = $dataTypeResolver;
        $this->columnOptionsResolver = $columnOptionsResolver;
    }

    public function getReverseAction(string $tableName, Action $action): ReverseAction
    {
        if ($action instanceof CreateTable) {
            return new ReverseAction($tableName, new DropTable());
        }

        if ($action instanceof AddColumn) {
            return new ReverseAction($tableName, new RemoveColumn($action->getColumn()->getName()));
        }

        if ($action instanceof RemoveColumn) {
            return new ReverseAction($tableName, new AddColumn($this->getCurrentColumnDefinition($tableName, $action->getName())));
        }

        if ($action instanceof ChangeColumn) {
            return new ReverseAction($tableName, new ChangeColumn($this->getCurrentColumnDefinition($tableName, $action->getName())));
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

        //if ($action instanceof RenameColumn) {
        //    return new RenameColumn($action->getNewName(), $action->getOldName());
        //}
        var_dump($action);

        throw new IrreversibleAction(get_class($action));
    }

    private function getCurrentColumnDefinition(string $tableName, string $columnName): Column
    {
        $sql = '
            SELECT column_default, is_nullable, data_type, character_maximum_length, numeric_precision, numeric_precision_radix, numeric_scale
            FROM information_schema.columns
            WHERE table_name = :tableName
            AND column_name = :columnName;
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'  => $tableName,
            'columnName' => $columnName,
        ]);

        $columnDefinition = $statement->fetch();

        if (!$columnDefinition) {
            throw new \Exception('Could not find current column definition');
        }

        $columnInformation = new ColumnInformation(
            $tableName,
            $columnName,
            ColumnDefinition::fromInformationSchemaRecord($columnDefinition),
        );

        $dataType = $this->dataTypeResolver->resolve($columnInformation);

        return new Column(
            $columnName,
            $dataType,
            $this->columnOptionsResolver->resolve($dataType, $columnInformation),
        );
    }
}
