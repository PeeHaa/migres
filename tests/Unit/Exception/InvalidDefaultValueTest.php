<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\InvalidDefaultValue;
use PHPUnit\Framework\TestCase;

class InvalidDefaultValueTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Default value of type `string` is not valid.');

        throw new InvalidDefaultValue('defaultValue');
    }

    public function testExceptionMessageWhenValueIsAnObject(): void
    {
        $this->expectExceptionMessage('Default value of type `DateTimeImmutable` is not valid.');

        throw new InvalidDefaultValue(new \DateTimeImmutable());
    }
}
