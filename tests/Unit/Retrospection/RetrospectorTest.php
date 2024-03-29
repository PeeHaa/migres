<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Retrospection;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddForeignByQuery;
use PeeHaa\Migres\Action\AddForeignKey;
use PeeHaa\Migres\Action\AddIndex;
use PeeHaa\Migres\Action\AddIndexByQuery;
use PeeHaa\Migres\Action\AddNamedPrimaryKeyByQuery;
use PeeHaa\Migres\Action\AddPrimaryKey;
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
use PeeHaa\Migres\Action\RenameColumn;
use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Action\RenameTable;
use PeeHaa\Migres\Constraint\Check;
use PeeHaa\Migres\Constraint\ForeignKey;
use PeeHaa\Migres\Constraint\Index;
use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Constraint\Unique;
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Exception\CheckDefinitionNotFound;
use PeeHaa\Migres\Exception\ColumnDefinitionNotFound;
use PeeHaa\Migres\Exception\ForeignKeyDefinitionNotFound;
use PeeHaa\Migres\Exception\IndexDefinitionNotFound;
use PeeHaa\Migres\Exception\IrreversibleAction;
use PeeHaa\Migres\Exception\PrimaryKeyDefinitionNotFound;
use PeeHaa\Migres\Exception\UniqueConstraintDefinitionNotFound;
use PeeHaa\Migres\Migration\Queries;
use PeeHaa\Migres\Retrospection\ColumnOptionsResolver;
use PeeHaa\Migres\Retrospection\DataTypeResolver;
use PeeHaa\Migres\Retrospection\Retrospector;
use PeeHaa\Migres\Retrospection\Sequence;
use PeeHaa\Migres\Specification\Column;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RetrospectorTest extends TestCase
{
    /** @var MockObject|\PDO */
    private MockObject $dbConnection;

    private ?Retrospector $retrospector;

    public function setUp(): void
    {
        $this->dbConnection = $this->createMock(\PDO::class);

        $this->retrospector = new Retrospector(
            $this->dbConnection,
            new DataTypeResolver(new Sequence()),
            new ColumnOptionsResolver(new Sequence()),
        );
    }

    public function testGetReverseActionForCreateTable(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(new CreateTable(new Label('table_name')));

        $this->assertInstanceOf(DropTable::class, $reverseAction);
    }

    public function testGetReverseActionForRenameTable(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new RenameTable(new Label('old_name'), new Label('new_name')),
        );

        $this->assertInstanceOf(RenameTable::class, $reverseAction);
    }

    public function testGetReverseActionForDropTable(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(new DropTable(new Label('table_name')));

        $this->assertInstanceOf(CreateTable::class, $reverseAction);
    }

    public function testGetReverseActionForAddColumn(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(new AddColumn(new Label('table_name'), new Column(
            new Label('column_name'),
            new IntegerType(),
        )));

        $this->assertInstanceOf(DropColumn::class, $reverseAction);
    }

    public function testGetReverseActionForDropColumnThrowsWhenColumnDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(ColumnDefinitionNotFound::class);

        $this->retrospector->getReverseAction(
            new DropColumn(new Label('table_name'), new Label('column_name')),
        );
    }

    public function testGetReverseActionForDropColumn(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => null,
                'is_nullable'              => 'YES',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropColumn(new Label('table_name'), new Label('column_name')),
        );

        $this->assertInstanceOf(AddColumn::class, $reverseAction);
    }

    public function testGetReverseActionForDropColumnSetsNotNullable(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropColumn(new Label('table_name'), new Label('column_name')),
        );

        $this->assertInstanceOf(AddColumn::class, $reverseAction);
    }

    public function testGetReverseActionForDropColumnSetsDefault(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => 12,
                'is_nullable'              => 'YES',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropColumn(new Label('table_name'), new Label('column_name')),
        );

        $this->assertInstanceOf(AddColumn::class, $reverseAction);
    }

    public function testGetReverseActionForRenameColumn(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new RenameColumn(new Label('table_name'), new Label('old_name'), new Label('new_name')),
        );

        $this->assertInstanceOf(RenameColumn::class, $reverseAction);
    }

    public function testGetReverseActionForChangeColumnThrowsWhenColumnDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(ColumnDefinitionNotFound::class);

        $this->retrospector->getReverseAction(new ChangeColumn(new Label('table_name'), new Column(
            new Label('column_name'),
            new IntegerType(),
        )));
    }

    public function testGetReverseActionForChangeColumn(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => null,
                'is_nullable'              => 'YES',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(new ChangeColumn(new Label('table_name'), new Column(
            new Label('column_name'),
            new IntegerType(),
        )));

        $this->assertInstanceOf(ChangeColumn::class, $reverseAction);
    }

    public function testGetReverseActionForChangeColumnSetsNotNullable(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => null,
                'is_nullable'              => 'NO',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(new ChangeColumn(new Label('table_name'), new Column(
            new Label('column_name'),
            new IntegerType(),
        )));

        $this->assertInstanceOf(ChangeColumn::class, $reverseAction);
    }

    public function testGetReverseActionForChangeColumnSetsDefault(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'column_default'           => 12,
                'is_nullable'              => 'YES',
                'data_type'                => 'integer',
                'character_maximum_length' => null,
                'numeric_precision'        => null,
                'numeric_scale'            => null,
            ])
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(new ChangeColumn(new Label('table_name'), new Column(
            new Label('column_name'),
            new IntegerType(),
        )));

        $this->assertInstanceOf(ChangeColumn::class, $reverseAction);
    }

    public function testGetReverseActionForAddPrimaryKey(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new AddPrimaryKey(
                new Label('table_name'),
                new PrimaryKey(new Label('table_name_pkey'), new Label('column_name')),
            ),
        );

        $this->assertInstanceOf(DropPrimaryKey::class, $reverseAction);
    }

    public function testGetReverseActionForDropPrimaryKeyThrowsWhenPrimaryKeyDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(PrimaryKeyDefinitionNotFound::class);

        $this->retrospector->getReverseAction(
            new DropPrimaryKey(new Label('table_name'), new Label('table_name_pkey')),
        );
    }

    public function testGetReverseActionForDropPrimaryKey(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('definition')
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropPrimaryKey(new Label('table_name'), new Label('table_name_pkey')),
        );

        $this->assertInstanceOf(AddNamedPrimaryKeyByQuery::class, $reverseAction);
    }

    public function testGetReverseActionForRenamePrimaryKey(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new RenamePrimaryKey(new Label('table_name'), new Label('old_name'), new Label('new_name')),
        );

        $this->assertInstanceOf(RenamePrimaryKey::class, $reverseAction);
    }

    public function testGetReverseActionForAddUniqueConstraint(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new AddUniqueConstraint(new Label('table_name'), new Unique(new Label('unique'), new Label('column_name'))),
        );

        $this->assertInstanceOf(DropUniqueConstraint::class, $reverseAction);
    }

    public function testGetReverseActionForDropUniqueConstraintThrowsWhenUniqueConstraintDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(UniqueConstraintDefinitionNotFound::class);

        $this->retrospector->getReverseAction(new DropUniqueConstraint(new Label('table_name'), new Label('unique')));
    }

    public function testGetReverseActionForDropUniqueConstraint(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('definition')
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropUniqueConstraint(new Label('table_name'), new Label('unique')),
        );

        $this->assertInstanceOf(AddUniqueConstraintByQuery::class, $reverseAction);
    }

    public function testGetReverseActionForAddIndex(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new AddIndex(
                new Label('table_name'),
                new Index(new Label('index_name'), new Label('table_name'), ['column_name']),
            ),
        );

        $this->assertInstanceOf(DropIndex::class, $reverseAction);
    }

    public function testGetReverseActionForDropIndexThrowsWhenIndexDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(IndexDefinitionNotFound::class);

        $this->retrospector->getReverseAction(
            new DropIndex(new Label('table_name'), new Label('index_name')),
        );
    }

    public function testGetReverseActionForDropIndex(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('CREATE INDEX index_name ON table_name (column_name)')
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropIndex(new Label('table_name'), new Label('index_name')),
        );

        $this->assertInstanceOf(AddIndexByQuery::class, $reverseAction);
    }

    public function testGetReverseActionForAddCheck(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new AddCheck(new Label('table_name'), new Check(new Label('check_name'), 'column_name > 10')),
        );

        $this->assertInstanceOf(DropCheck::class, $reverseAction);
    }

    public function testGetReverseActionForDropCheckThrowsWhenIndexDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(CheckDefinitionNotFound::class);

        $this->retrospector->getReverseAction(
            new DropCheck(new Label('table_name'), new Label('check_name')),
        );
    }

    public function testGetReverseActionForDropCheck(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('column_name > 10')
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropCheck(new Label('table_name'), new Label('check_name')),
        );

        $this->assertInstanceOf(AddCheck::class, $reverseAction);
    }

    public function testGetReverseActionForAddForeignKey(): void
    {
        $reverseAction = $this->retrospector->getReverseAction(
            new AddForeignKey(new Label('table_name'), new ForeignKey(new Label('name_fkey'), [new Label('referenced_column')], new Label('referenced_table'), [new Label('id')])),
        );

        $this->assertInstanceOf(DropForeignKey::class, $reverseAction);
    }

    public function testGetReverseActionForDropForeignKey(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('REFERENCES referenced_table (id) CONSTRAINT name_fkey FOREIGN KEY (customer_id) REFERENCES customers (id)')
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $reverseAction = $this->retrospector->getReverseAction(
            new DropForeignKey(new Label('table_name'), new Label('name_fkey')),
        );

        $this->assertInstanceOf(AddForeignByQuery::class, $reverseAction);
    }

    public function testGetReverseActionForDropForeignKeyThrowsWhenDefinitionCanNotBeFound(): void
    {
        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $statement
            ->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(false)
        ;

        $this->dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        $this->expectException(ForeignKeyDefinitionNotFound::class);
        $this->expectExceptionMessage('Could not find definition of foreign key `name_fkey`');

        $this->retrospector->getReverseAction(
            new DropForeignKey(new Label('table_name'), new Label('name_fkey')),
        );
    }

    public function testGetReverseActionThrowsWhenActionCanNotBeReversed(): void
    {
        $irreversibleAction = new class implements Action
        {
            public function toQueries(): Queries
            {
                return new Queries('SELECT 1');
            }
        };

        $this->expectException(IrreversibleAction::class);

        $this->retrospector->getReverseAction($irreversibleAction);
    }
}
