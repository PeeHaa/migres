<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\ForeignKey;
use PeeHaa\Migres\Exception\ForeignKeyColumnMismatch;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class ForeignKeyTest extends TestCase
{
    public function testConstructorThrowsOnInvalidColumnNameType(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 passed to PeeHaa\Migres\Constraint\ForeignKey::__construct() must be of an array of PeeHaa\Migres\Specification\Label, string given');

        new ForeignKey(new Label('index_name'), ['referenced_id'], new Label('reference_table'), [new Label('id')]);
    }

    public function testConstructorThrowsOnInvalidReferencedColumnNameType(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 4 passed to PeeHaa\Migres\Constraint\ForeignKey::__construct() must be of an array of PeeHaa\Migres\Specification\Label, string given');

        new ForeignKey(new Label('index_name'), [new Label('referenced_id')], new Label('reference_table'), ['id']);
    }

    public function testConstructorThrowsOnInvalidReferencedColumnNameTypeWhenObject(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 4 passed to PeeHaa\Migres\Constraint\ForeignKey::__construct() must be of an array of PeeHaa\Migres\Specification\Label, DateTimeImmutable given');

        new ForeignKey(new Label('index_name'), [new Label('referenced_id')], new Label('reference_table'), [new \DateTimeImmutable()]);
    }

    public function testConstructorThrowsWhenColumnCountDoesNotMatch(): void
    {
        $this->expectException(ForeignKeyColumnMismatch::class);
        $this->expectExceptionMessage('Column count in foreign key constraint (1) must match referenced column count (2)');

        new ForeignKey(new Label('index_name'), [new Label('referenced_id')], new Label('reference_table'), [new Label('id'), new Label('column2')]);
    }

    public function testToSql(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteCascade(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteCascade()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE CASCADE ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteRestrict(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteRestrict()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE RESTRICT ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteNoAction(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteNoAction()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteCascadeOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteRestrict()->onDeleteCascade()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE CASCADE ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteRestrictOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteCascade()->onDeleteRestrict()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE RESTRICT ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnDeleteNoActionOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onDeleteCascade()->onDeleteNoAction()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnUpdateCascade(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateCascade()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE CASCADE', $sql);
    }

    public function testToSqlWithOnUpdateRestrict(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateRestrict()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE RESTRICT', $sql);
    }

    public function testToSqlWithOnUpdateNoAction(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateNoAction()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION', $sql);
    }

    public function testToSqlWithOnUpdateCascadeOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateRestrict()->onUpdateCascade()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE CASCADE', $sql);
    }

    public function testToSqlWithOnUpdateRestrictOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateCascade()->onUpdateRestrict()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE RESTRICT', $sql);
    }

    public function testToSqlWithOnUpdateNoActionOverrides(): void
    {
        $sql = (new ForeignKey(
            new Label('index_name'),
            [new Label('referenced_id')],
            new Label('reference_table'),
            [new Label('id')],
        ))->onUpdateCascade()->onUpdateNoAction()->toSql();

        $this->assertSame('CONSTRAINT "index_name" FOREIGN KEY ("referenced_id") REFERENCES reference_table ("id") ON DELETE NO ACTION ON UPDATE NO ACTION', $sql);
    }
}
