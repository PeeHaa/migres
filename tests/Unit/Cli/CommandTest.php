<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\Command;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testRender(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('  commandName')
        ;

        (new Command($climate, 'commandName', 'helpText'))->render();
    }
}
