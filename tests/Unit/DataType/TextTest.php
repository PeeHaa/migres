<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\DataType;

use PeeHaa\Migres\DataType\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('text', (new Text())->toSql());
    }
}
