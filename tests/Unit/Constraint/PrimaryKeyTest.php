<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Constraint;

use PeeHaa\Migres\Constraint\PrimaryKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class PrimaryKeyTest extends TestCase
{
    public function testToSqlWithASingleColumn(): void
    {
        $this->assertSame(
            'CONSTRAINT "table_name_pkey" PRIMARY KEY ("column1")',
            (new PrimaryKey(new Label('table_name_pkey'), new Label('column1')))->toSql(),
        );
    }

    public function testToSqlWithMultipleColumns(): void
    {
        $this->assertSame(
            'CONSTRAINT "table_name_pkey" PRIMARY KEY ("column1", "column2")',
            (new PrimaryKey(new Label('table_name_pkey'), new Label('column1'), new Label('column2')))->toSql(),
        );
    }
}
