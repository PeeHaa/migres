<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddCheckByQuery;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddConstraint;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddIndexByQuery;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropCheck;
use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropIndex;
use PeeHaa\Migres\Action\DropPrimaryKey;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\DropUniqueConstraint;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\Exception\IrreversibleAction;
use PeeHaa\Migres\Specification\Column;

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

    public function getReverseAction(Action $action): Action
    {
        if ($action instanceof CreateTable) {
            return new DropTable($action->getTableName());
        }

        if ($action instanceof RenameTable) {
            return new RenameTable($action->getNewName(), $action->getOldName());
        }

        if ($action instanceof DropTable) {
            return new CreateTable($action->getTableName());
        }

        if ($action instanceof AddColumn) {
            return new DropColumn($action->getTableName(), $action->getColumn()->getName());
        }

        if ($action instanceof DropColumn) {
            return new AddColumn(
                $action->getTableName(),
                $this->getCurrentColumnDefinition($action->getTableName(), $action->getName()),
            );
        }

        if ($action instanceof RenameColumn) {
            return new RenameColumn($action->getTableName(), $action->getNewName(), $action->getOldName());
        }

        if ($action instanceof ChangeColumn) {
            return new ChangeColumn(
                $action->getTableName(),
                $this->getCurrentColumnDefinition($action->getTableName(), $action->getName()),
            );
        }

        if ($action instanceof AddPrimaryKey) {
            return new DropPrimaryKey($action->getTableName(), $action->getCombinedPrimaryKey()->getName());
        }

        if ($action instanceof DropPrimaryKey) {
            return new AddPrimaryKey(
                $action->getTableName(),
                $this->getCurrentPrimaryKeyDefinition($action->getTableName(), $action->getName())
            );
        }

        if ($action instanceof RenamePrimaryKey) {
            return new RenamePrimaryKey($action->getTableName(), $action->getNewName(), $action->getOldName());
        }

        if ($action instanceof AddUniqueConstraint) {
            return new DropUniqueConstraint($action->getTableName(), $action->getConstraint()->getName());
        }

        if ($action instanceof DropUniqueConstraint) {
            return new AddUniqueConstraint(
                $action->getTableName(),
                $this->getCurrentUniqueConstraintDefinition($action->getTableName(), $action->getName())
            );
        }

        if ($action instanceof AddIndex) {
            return new DropIndex($action->getTableName(), $action->getIndex()->getName());
        }

        if ($action instanceof DropIndex) {
            return new AddIndexByQuery(
                $action->getTableName(),
                $this->getCurrentIndexDefinition($action->getTableName(), $action->getName())
            );
        }

        if ($action instanceof AddCheck) {
            return new DropCheck($action->getTableName(), $action->getCheck()->getName());
        }

        if ($action instanceof DropCheck) {
            return new AddCheck(
                $action->getTableName(),
                $this->getCurrentCheckDefinition($action->getTableName(), $action->getName()),
            );
        }

        throw new IrreversibleAction(get_class($action));
    }

    private function getCurrentColumnDefinition(string $tableName, string $columnName): Column
    {
        $sql = '
            SELECT column_default, is_nullable, data_type, character_maximum_length, numeric_precision, numeric_scale
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

        $column = new Column($columnName, $dataType);

        $columnOptions = $this->columnOptionsResolver->resolve($dataType, $columnInformation);

        if (!$columnOptions->isNullable()) {
            $column->notNull();
        }

        if ($columnOptions->hasDefault()) {
            $column->default($columnOptions->getDefaultValue($column));
        }

        return $column;
    }

    private function getCurrentPrimaryKeyDefinition(string $tableName, string $name): PrimaryKey
    {
        $sql = '
            SELECT
                table_constraints.constraint_name, table_constraints.constraint_type, table_constraints.table_name, key_column_usage.column_name, 
                constraint_column_usage.table_name AS foreign_table_name,
                constraint_column_usage.column_name AS foreign_column_name 
            FROM 
                information_schema.table_constraints
                JOIN information_schema.key_column_usage ON table_constraints.constraint_name = key_column_usage.constraint_name
                JOIN information_schema.constraint_column_usage ON constraint_column_usage.constraint_name = table_constraints.constraint_name
                WHERE table_constraints.table_name = :tableName
                    AND table_constraints.constraint_name = :constraintName
                    AND table_constraints.constraint_type = \'PRIMARY KEY\'
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName,
            'constraintName' => $name,
        ]);

        $constraintInfo = $statement->fetchAll();

        if (!$constraintInfo) {
            throw new \Exception('Could not find current definition of primary key');
        }

        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new PrimaryKey($name, ...array_unique($columns));
    }

    private function getCurrentUniqueConstraintDefinition(string $tableName, string $name): Unique
    {
        $sql = '
            SELECT
                table_constraints.constraint_name, table_constraints.constraint_type, table_constraints.table_name, key_column_usage.column_name, 
                constraint_column_usage.table_name AS foreign_table_name,
                constraint_column_usage.column_name AS foreign_column_name 
            FROM 
                information_schema.table_constraints
                JOIN information_schema.key_column_usage ON table_constraints.constraint_name = key_column_usage.constraint_name
                JOIN information_schema.constraint_column_usage ON constraint_column_usage.constraint_name = table_constraints.constraint_name
                WHERE table_constraints.table_name = :tableName
                    AND table_constraints.constraint_name = :constraintName
                    AND table_constraints.constraint_type = \'UNIQUE\'
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName,
            'constraintName' => $name,
        ]);

        $constraintInfo = $statement->fetchAll();

        if (!$constraintInfo) {
            throw new \Exception('Could not find current definition of unique constraint');
        }

        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new Unique($name, ...array_unique($columns));
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

    private function getPrimaryKeyConstraint(array $constraintInfo): PrimaryKey
    {
        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new PrimaryKey($constraintInfo[0]['constraint_name'], ...array_unique($columns));
    }

    private function getIndex(array $constraintInfo): Unique
    {
        $columns = [];

        foreach ($constraintInfo as $constraintRecord) {
            $columns[] = $constraintRecord['column_name'];
        }

        return new Unique($constraintInfo[0]['constraint_name'], ...array_unique($columns));
    }

    private function getCurrentIndexDefinition(string $tableName, string $indexName): string
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
            'indexName' => $indexName,
        ]);

        $indexInformation = $statement->fetchColumn(0);

        if (!$indexInformation) {
            throw new \Exception('Could not find current constraint definition');
        }

        return $indexInformation;
    }

    private function getCurrentCheckDefinition(string $tableName, string $checkName): Check
    {
        $sql = '
            SELECT pg_constraint.consrc
            FROM pg_catalog.pg_constraint
            INNER JOIN pg_catalog.pg_class ON pg_class.oid = pg_constraint.conrelid
            WHERE pg_class.relname = :tableName
                AND pg_constraint.conname = :constraintName;
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName,
            'constraintName' => $checkName,
        ]);

        $checkInformation = $statement->fetchColumn(0);

        if (!$checkInformation) {
            throw new \Exception('Could not find current check definition');
        }

        return new Check($checkName, $checkInformation);
    }
}
