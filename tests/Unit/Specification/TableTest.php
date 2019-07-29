<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Specification;

use PeeHaa\Migres\Action\AddCheck;
use PeeHaa\Migres\Action\AddColumn;
use PeeHaa\Migres\Action\AddIndex;
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
use PeeHaa\Migres\DataType\IntegerType;
use PeeHaa\Migres\Specification\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function testFromCreateTable(): void
    {
        $table = Table::fromCreateTable('table_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(CreateTable::class, $action);
        }
    }

    public function testFromChangeTable(): void
    {
        $table = Table::fromChangeTable('table_name');

        $this->assertCount(0, $table->getActions());
    }

    public function testFromRenameTable(): void
    {
        $table = Table::fromRenameTable('old_name', 'name_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(RenameTable::class, $action);
        }
    }

    public function testFromDropTable(): void
    {
        $table = Table::fromDropTable('table_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropTable::class, $action);
        }
    }

    public function testAddColumn(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addColumn('column_name', new IntegerType());

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddColumn::class, $action);
        }
    }

    public function testDropColumn(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->dropColumn('column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropColumn::class, $action);
        }
    }

    public function testRenameColumn(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->renameColumn('old_name', 'new_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(RenameColumn::class, $action);
        }
    }

    public function testChangeColumn(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->changeColumn('column_name', new IntegerType());

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(ChangeColumn::class, $action);
        }
    }

    public function testPrimaryKey(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->primaryKey('column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddPrimaryKey::class, $action);
        }
    }

    public function testNamedPrimaryKey(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->namedPrimaryKey('table_name_pkey', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddPrimaryKey::class, $action);
        }
    }

    public function testDropPrimaryKey(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->dropPrimaryKey();

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropPrimaryKey::class, $action);
        }
    }

    public function testRenamePrimaryKey(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->renamePrimaryKey('old_name', 'new_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(RenamePrimaryKey::class, $action);
        }
    }

    public function testAddUniqueConstraint(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addUniqueConstraint('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddUniqueConstraint::class, $action);
        }
    }

    public function testDropUniqueConstraint(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->dropUniqueConstraint('name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropUniqueConstraint::class, $action);
        }
    }

    public function testAddIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addIndex('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddIndex::class, $action);
        }
    }

    public function testAddBtreeIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addBtreeIndex('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddIndex::class, $action);
        }
    }

    public function testAddHashIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addHashIndex('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddIndex::class, $action);
        }
    }

    public function testAddGistIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addGistIndex('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddIndex::class, $action);
        }
    }

    public function testAddGinIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addGinIndex('name', 'column_name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddIndex::class, $action);
        }
    }

    public function testDropIndex(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->dropIndex('name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropIndex::class, $action);
        }
    }

    public function testAddCheck(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->addCheck('name', 'column_name > 10');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(AddCheck::class, $action);
        }
    }

    public function testDropCheck(): void
    {
        $table = Table::fromChangeTable('table_name');

        $table->dropCheck('name');

        $this->assertCount(1, $table->getActions());

        foreach ($table->getActions() as $action) {
            $this->assertInstanceOf(DropCheck::class, $action);
        }
    }
}
