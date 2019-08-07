<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Exception;

use PeeHaa\Migres\Exception\LabelTooLong;
use PHPUnit\Framework\TestCase;

class LabelTooLongTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectException(LabelTooLong::class);
        $this->expectExceptionMessage(
            'Label (`this_label_is_longer_than_the_default_limit_of_63_bytes_so_it_throws`) is longer than the maximum label length of 63.'
        );

        throw new LabelTooLong('this_label_is_longer_than_the_default_limit_of_63_bytes_so_it_throws');
    }
}
