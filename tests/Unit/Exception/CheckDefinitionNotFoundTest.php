<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\CheckDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class CheckDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Could not find definition of check `key_name` in table `table_name`.');

        throw new CheckDefinitionNotFound('table_name', 'key_name');
    }
}
