<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\UniqueConstraintDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class UniqueConstraintDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage(
            'Could not find definition of unique constraint `key_name` in table `table_name`.',
        );

        throw new UniqueConstraintDefinitionNotFound('table_name', 'key_name');
    }
}
