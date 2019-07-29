<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\Unique;
use PHPUnit\Framework\TestCase;

class UniqueTest extends TestCase
{
    public function testToSqlWithASingleColumn(): void
    {
        $this->assertSame(
            'CONSTRAINT "table_name_pkey" UNIQUE ("column1")',
            (new Unique('table_name_pkey', 'column1'))->toSql()
        );
    }

    public function testToSqlWithMultipleColumns(): void
    {
        $this->assertSame(
            'CONSTRAINT "table_name_pkey" UNIQUE ("column1", "column2")',
            (new Unique('table_name_pkey', 'column1', 'column2'))->toSql()
        );
    }
}
