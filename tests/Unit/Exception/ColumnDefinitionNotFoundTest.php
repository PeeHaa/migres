<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\ColumnDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class ColumnDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Could not find definition of column `table_name.column_name`.');

        throw new ColumnDefinitionNotFound('table_name', 'column_name');
    }
}
