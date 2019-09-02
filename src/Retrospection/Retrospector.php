<?php declare(strict_types=1);

namespace PeeHaa\Migres\Retrospection;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddForeignByQuery;
use PeeHaa\Migres\Action\AddForeignKey;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddIndexByQuery;
use PeeHaa\Migres\Action\AddNamedPrimaryKeyByQuery;
use PeeHaa\Migres\Action\AddPrimaryKey;
use PeeHaa\Migres\Action\AddPrimaryKeyByQuery;
use PeeHaa\Migres\Action\AddTableComment;
use PeeHaa\Migres\Action\AddUniqueConstraint;
use PeeHaa\Migres\Action\AddUniqueConstraintByQuery;
use PeeHaa\Migres\Action\ChangeColumn;
use PeeHaa\Migres\Action\CreateTable;
use PeeHaa\Migres\Action\DropCheck;
use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropForeignKey;
use PeeHaa\Migres\Action\DropIndex;
use PeeHaa\Migres\Action\DropPrimaryKey;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Action\DropUniqueConstraint;
use PeeHaa\Migres\Action\RemoveTableComment;
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\Exception\CheckDefinitionNotFound;
use PeeHaa\Migres\Exception\ColumnDefinitionNotFound;
use PeeHaa\Migres\Exception\CommentDefinitionNotFound;
use PeeHaa\Migres\Exception\ForeignKeyDefinitionNotFound;
use PeeHaa\Migres\Exception\IndexDefinitionNotFound;
use PeeHaa\Migres\Exception\IrreversibleAction;
use PeeHaa\Migres\Exception\PrimaryKeyDefinitionNotFound;
use PeeHaa\Migres\Exception\UniqueConstraintDefinitionNotFound;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;

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
            return new DropPrimaryKey($action->getTableName(), $action->getPrimaryKey()->getName());
        }

        if ($action instanceof DropPrimaryKey) {
            return $this->getCurrentPrimaryKeyDefinition($action->getTableName(), $action->getName());
        }

        if ($action instanceof RenamePrimaryKey) {
            return new RenamePrimaryKey($action->getTableName(), $action->getNewName(), $action->getOldName());
        }

        if ($action instanceof AddUniqueConstraint) {
            return new DropUniqueConstraint($action->getTableName(), $action->getConstraint()->getName());
        }

        if ($action instanceof DropUniqueConstraint) {
            return $this->getCurrentUniqueConstraintDefinition($action->getTableName(), $action->getName());
        }

        if ($action instanceof AddIndex) {
            return new DropIndex($action->getTableName(), $action->getIndex()->getName());
        }

        if ($action instanceof DropIndex) {
            return new AddIndexByQuery(
                $action->getTableName(),
                $this->getCurrentIndexDefinition($action->getTableName(), $action->getName()),
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

        if ($action instanceof AddForeignKey) {
            return new DropForeignKey($action->getTableName(), $action->getForeignKey()->getName());
        }

        if ($action instanceof DropForeignKey) {
            return $this->getCurrentForeignKeyDefinition($action->getTableName(), $action->getName());
        }

        if ($action instanceof AddTableComment) {
            return $this->getCurrentTableCommentDefinition($action->getTableName());
        }

        if ($action instanceof RemoveTableComment) {
            return $this->getCurrentTableCommentDefinition($action->getTableName());
        }

        throw new IrreversibleAction(get_class($action));
    }

    private function getCurrentColumnDefinition(Label $tableName, Label $columnName): Column
    {
        $sql = '
            SELECT column_default, is_nullable, data_type, character_maximum_length, numeric_precision, numeric_scale
            FROM information_schema.columns
            WHERE table_name = :tableName
            AND column_name = :columnName;
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'  => $tableName->toString(),
            'columnName' => $columnName->toString(),
        ]);

        $columnDefinition = $statement->fetch();

        if (!$columnDefinition) {
            throw new ColumnDefinitionNotFound($tableName->toString(), $columnName->toString());
        }

        $columnInformation = new ColumnInformation(
            $tableName,
            $columnName,
            ColumnDefinition::fromInformationSchemaRecord($columnDefinition),
        );

        $dataType = $this->dataTypeResolver->resolve($columnInformation);

        $column = new Column($tableName, $columnName, $dataType);

        $columnOptions = $this->columnOptionsResolver->resolve($columnInformation);

        if (!$columnOptions->isNullable()) {
            $column->notNull();
        }

        if ($columnOptions->hasDefault()) {
            $column->default($columnOptions->getDefaultValue($column));
        }

        return $column;
    }

    private function getCurrentPrimaryKeyDefinition(Label $tableName, Label $name): AddNamedPrimaryKeyByQuery
    {
        $sql = '
            SELECT pg_get_constraintdef(pg_constraint.oid) AS definition
            FROM pg_constraint
            JOIN pg_namespace ON pg_namespace.oid = pg_constraint.connamespace
            WHERE pg_namespace.nspname = \'public\'
                AND conrelid = :tableName::regclass
                AND contype = \'p\'
                AND conname = :constraintName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName->toString(),
            'constraintName' => $name->toString(),
        ]);

        $constraintInfo = $statement->fetchColumn(0);

        if (!$constraintInfo) {
            throw new PrimaryKeyDefinitionNotFound($tableName->toString(), $name->toString());
        }

        return new AddNamedPrimaryKeyByQuery($tableName, $name, $constraintInfo);
    }

    private function getCurrentUniqueConstraintDefinition(Label $tableName, Label $name): AddUniqueConstraintByQuery
    {
        $sql = '
            SELECT pg_get_constraintdef(pg_constraint.oid) AS definition
            FROM pg_constraint
            JOIN pg_namespace ON pg_namespace.oid = pg_constraint.connamespace
            WHERE pg_namespace.nspname = \'public\'
                AND conrelid = :tableName::regclass
                AND contype = \'u\'
                AND conname = :constraintName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName'      => $tableName->toString(),
            'constraintName' => $name->toString(),
        ]);

        $constraintInfo = $statement->fetchColumn(0);

        if (!$constraintInfo) {
            throw new UniqueConstraintDefinitionNotFound($tableName->toString(), $name->toString());
        }

        return new AddUniqueConstraintByQuery($tableName, $name, $constraintInfo);
    }

    private function getCurrentIndexDefinition(Label $tableName, Label $indexName): string
    {
        $sql = '
            SELECT indexdef
            FROM pg_indexes
            WHERE tablename = :tableName
              AND indexname = :indexName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName' => $tableName->toString(),
            'indexName' => $indexName->toString(),
        ]);

        $indexInformation = $statement->fetchColumn(0);

        if (!$indexInformation) {
            throw new IndexDefinitionNotFound($tableName->toString(), $indexName->toString());
        }

        return $indexInformation;
    }

    private function getCurrentCheckDefinition(Label $tableName, Label $checkName): Check
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
            'tableName'      => $tableName->toString(),
            'constraintName' => $checkName->toString(),
        ]);

        $checkInformation = $statement->fetchColumn(0);

        if (!$checkInformation) {
            throw new CheckDefinitionNotFound($tableName->toString(), $checkName->toString());
        }

        return new Check($checkName, $checkInformation);
    }

    private function getCurrentForeignKeyDefinition(Label $tableName, Label $name): AddForeignByQuery
    {
        $sql = '
            SELECT pg_get_constraintdef(pg_constraint.oid)
            FROM pg_constraint
            JOIN pg_namespace ON pg_namespace.oid = pg_constraint.connamespace
            WHERE contype = \'f\'
                AND conname = :constraintName;
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'constraintName' => $name->toString(),
        ]);

        $constraintInfo = $statement->fetchColumn(0);

        if (!$constraintInfo) {
            throw new ForeignKeyDefinitionNotFound($name->toString());
        }

        return new AddForeignByQuery($tableName, $name, $constraintInfo);
    }

    /**
     * @return AddTableComment|RemoveTableComment
     */
    private function getCurrentTableCommentDefinition(Label $tableName): Action
    {
        $sql = '
            SELECT obj_description(oid)
            FROM pg_class
            WHERE relkind = \'r\'
                AND relname = :tableName
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'tableName' => $tableName->toString(),
        ]);

        $comment = $statement->fetchColumn(0);

        if ($comment === false) {
            throw new CommentDefinitionNotFound($tableName->toString());
        }

        if ($comment === null) {
            return new RemoveTableComment($tableName);
        }

        return new AddTableComment($tableName, $comment);
    }
}
