<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddConstraint;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddIndexByQuery;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\RemoveColumn;
use PeeHaa\Migres\Action\RemoveConstraint;
use PeeHaa\Migres\Action\RemoveIndex;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Action\ReverseAction;
use PeeHaa\Migres\Column;
use PeeHaa\Migres\Constraint\CombinedPrimaryKey;
use PeeHaa\Migres\Constraint\CombinedUnique;
use PeeHaa\Migres\Constraint\Constraint;
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

    public function getReverseAction(string $originalTableName, string $tableName, Action $action): ReverseAction
    {
        if ($action instanceof CreateTable) {
            return new ReverseAction($originalTableName, new DropTable());
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

        if ($action instanceof RenameColumn) {
            return new ReverseAction($tableName, new RenameColumn($action->getNewName(), $action->getOldName()));
        }

        if ($action instanceof RenameTable) {
            return new ReverseAction($tableName, new RenameTable($action->getNewName(), $action->getOriginalName()));
        }

        if ($action instanceof AddConstraint) {
            return new ReverseAction($tableName, new RemoveConstraint($action->getConstraint()->getName()));
        }

        if ($action instanceof AddIndex) {
            return new ReverseAction($tableName, new RemoveIndex($action->getIndex()->getName()));
        }

        if ($action instanceof AddPrimaryKey) {
            return new ReverseAction($tableName, new RemoveConstraint($action->getCombinedPrimaryKey()->getName()));
        }

        if ($action instanceof RemoveConstraint) {
            return new ReverseAction($tableName, $this->getCurrentConstraintDefinition($tableName, $action->getName()));
        }

        if ($action instanceof RemoveIndex) {
            return new ReverseAction($tableName, $this->getCurrentConstraintDefinition($tableName, $action->getName()));
        }

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

    private function getCurrentConstraintDefinition(string $tableName, string $constraintName): Action
    {
        $sql = '
            SELECT
                table_constraints.constraint_name, constraint_type, table_constraints.table_name, key_column_usage.column_name, 
                constraint_column_usage.table_name AS foreign_table_name,
                constraint_column_usage.column_name AS foreign_column_name 
            FROM 
                information_schema.table_constraints
                JOIN information_schema.key_column_usage ON table_constraints.constraint_name = key_column_usage.constraint_name
                JOIN information_schema.constraint_column_usage ON constraint_column_usage.constraint_name = table_constraints.constraint_name
                WHERE table_constraints.table_name = :tableName
                    AND table_constraints.constraint_name = :constraintName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName,
            'constraintName' => $constraintName,
        ]);

        $constraintInfo = $statement->fetchAll();

        if (isset($constraintInfo[0]) && $constraintInfo[0]['constraint_type']) {
            return new AddPrimaryKey($this->getPrimaryKeyConstraint($constraintInfo));
        }

        return new AddIndexByQuery($this->getIndexQuery($tableName, $constraintName));
    }

    private function getPrimaryKeyConstraint(array $constraintInfo): CombinedPrimaryKey
    {
        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new CombinedPrimaryKey($constraintInfo[0]['constraint_name'], ...array_unique($columns));
    }

    private function getIndex(array $constraintInfo): CombinedUnique
    {
        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new CombinedUnique($constraintInfo[0]['constraint_name'], ...array_unique($columns));
    }

    private function getIndexQuery(string $tableName, string $constraintName): string
    {
        $sql = '
            SELECT indexdef
            FROM pg_indexes
            WHERE tablename = :tableName
              AND indexname = :indexName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName' => $tableName,
            'indexName' => $constraintName,
        ]);

        $indexInformation = $statement->fetchColumn(0);

        if (!$indexInformation) {
            throw new \Exception('Could not find current constraint definition');
        }

        return $indexInformation;
    }
}
