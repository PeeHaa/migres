<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Specification;

use PeeHaa\Migres\Exception\LabelTooLong;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class LabelTest extends TestCase
{
    public function testConstructorThrowsWhenLabelIsTooLong(): void
    {
        $this->expectException(LabelTooLong::class);

        new Label('1234567890123456789012345678901234567890123456789012345678901234');
    }
}
