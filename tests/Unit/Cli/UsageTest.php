<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\Usage;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class UsageTest extends TestCase
{
    public function testRender(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('darkGray')
            ->with('usageText')
        ;

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        (new Usage($climate, 'usageText'))->render();
    }
}
