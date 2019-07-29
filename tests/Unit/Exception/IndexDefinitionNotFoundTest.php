<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\IndexDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class IndexDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Could not find definition of index `key_name` in table `table_name`.');

        throw new IndexDefinitionNotFound('table_name', 'key_name');
    }
}
