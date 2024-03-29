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

        $climate
            ->expects($this->once())
            ->method('white')
            ->with('    helpText')
        ;

        $climate
            ->expects($this->exactly(3))
            ->method('br')
        ;

        (new Command($climate, 'commandName', 'helpText'))->render();
    }

    public function testRenderWithExtraTexts(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('  commandName')
        ;

        $climate
            ->expects($this->once())
            ->method('white')
            ->with('    helpText')
        ;

        $climate
            ->expects($this->exactly(2))
            ->method('out')
            ->withConsecutive(['    extraText1'], ['    extraText2'])
        ;

        $climate
            ->expects($this->exactly(4))
            ->method('br')
        ;

        (new Command($climate, 'commandName', 'helpText', 'extraText1', 'extraText2'))->render();
    }
}
