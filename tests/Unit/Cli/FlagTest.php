<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\Flag;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class FlagTest extends TestCase
{
    public function testRender(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('  --flag')
        ;

        $climate
            ->expects($this->once())
            ->method('white')
            ->with('    helpText')
        ;

        $climate
            ->expects($this->exactly(3))
            ->method('br')
        ;

        (new Flag($climate, '--flag', 'helpText'))->render();
    }

    public function testRenderWithExtraTexts(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('  --flag')
        ;

        $climate
            ->expects($this->once())
            ->method('white')
            ->with('    helpText')
        ;

        $climate
            ->expects($this->once())
            ->method('out')
            ->with('    extraText')
        ;

        $climate
            ->expects($this->exactly(4))
            ->method('br')
        ;

        (new Flag($climate, '--flag', 'helpText', 'extraText'))->render();
    }
}
