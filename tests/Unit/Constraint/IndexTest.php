<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\Index;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testToSqlWithCustomMethod(): void
    {
        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" USING gin (column1)',
            (new Index('index_name', 'table_name', ['column1'], 'gin'))->toSql(),
        );
    }

    public function testToSqlWithCustomMethodAndMultipleColumns(): void
    {
        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" USING gin (column1, column2)',
            (new Index('index_name', 'table_name', ['column1', 'column2'], 'gin'))->toSql(),
        );
    }

    public function testToSqlWithoutMethod(): void
    {
        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" (column1)',
            (new Index('index_name', 'table_name', ['column1']))->toSql(),
        );
    }

    public function testToSqlWithoutMethodAndMultipleColumns(): void
    {
        $this->assertSame(
            'CREATE INDEX "index_name" ON "table_name" (column1, column2)',
            (new Index('index_name', 'table_name', ['column1', 'column2']))->toSql(),
        );
    }
}
