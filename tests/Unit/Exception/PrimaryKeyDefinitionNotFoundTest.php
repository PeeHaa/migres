<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\PrimaryKeyDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class PrimaryKeyDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Could not find definition of primary key `key_name` in table `table_name`.');

        throw new PrimaryKeyDefinitionNotFound('table_name', 'key_name');
    }
}
