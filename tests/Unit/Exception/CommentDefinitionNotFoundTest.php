<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\CommentDefinitionNotFound;
use PHPUnit\Framework\TestCase;

class CommentDefinitionNotFoundTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Could not find definition of comment for table `table_name`.');

        throw new CommentDefinitionNotFound('table_name');
    }
}
