<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\Binary;
use PeeHaa\Migres\Cli\Command;
use PeeHaa\Migres\Cli\Flag;
use PeeHaa\Migres\Cli\Usage;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class BinaryTest extends TestCase
{
    public function testTitleIsCorrectlyRendered(): void
    {
        $climate = $this->createMock(Climate::class);

        (new Binary($climate, 'The title'))->renderHelp();

        $climate
            ->expects($this->exactly(4))
            ->method('info')
            ->willReturnCallback(static function (string $content) use ($climate) {
                static $i = 0;

                if ($i === 0) {
                    self::assertSame('The title', $content);
                }

                $i++;

                return $climate;
            })
        ;

        (new Binary($climate, 'The title'))->renderHelp();
    }

    public function testHeadersAreCorrectlyRendered(): void
    {
        $climate = $this->createMock(Climate::class);

        (new Binary($climate, 'The title'))->renderHelp();

        $climate
            ->expects($this->exactly(4))
            ->method('info')
            ->withConsecutive(['The title'], ['Usage:'], ['Commands:'], ['Flags:'])
        ;

        (new Binary($climate, 'The title'))->renderHelp();
    }

    public function testAddUsage(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->exactly(2))
            ->method('darkGray')
            ->withConsecutive(['Usage text 1'], ['Usage text 2'])
        ;

        (new Binary($climate, 'The title'))
            ->addUsage(new Usage($climate, 'Usage text 1'))
            ->addUsage(new Usage($climate, 'Usage text 2'))
            ->renderHelp()
        ;
    }

    public function testAddCommand(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->exactly(5))
            ->method('info')
            ->withConsecutive(['The title'], ['Usage:'], ['Commands:'], ['  Command'], ['Flags:'])
        ;

        $climate
            ->expects($this->exactly(1))
            ->method('white')
            ->with('    Help text')
        ;

        (new Binary($climate, 'The title'))
            ->addCommand(new Command($climate, 'Command', 'Help text'))
            ->renderHelp()
        ;
    }

    public function testAddCommandWithExtraTexts(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->exactly(5))
            ->method('info')
            ->withConsecutive(['The title'], ['Usage:'], ['Commands:'], ['  Command'], ['Flags:'])
        ;

        $climate
            ->expects($this->exactly(1))
            ->method('white')
            ->with('    Help text')
        ;

        $climate
            ->expects($this->exactly(2))
            ->method('out')
            ->withConsecutive(['    Extra text 1'], ['    Extra text 2'])
        ;

        (new Binary($climate, 'The title'))
            ->addCommand(new Command($climate, 'Command', 'Help text', 'Extra text 1', 'Extra text 2'))
            ->renderHelp()
        ;
    }

    public function testAddFlag(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->exactly(5))
            ->method('info')
            ->withConsecutive(['The title'], ['Usage:'], ['Commands:'], ['Flags:'], ['  -flag'])
        ;

        $climate
            ->expects($this->exactly(1))
            ->method('white')
            ->with('    Help text')
        ;

        (new Binary($climate, 'The title'))
            ->addFlag(new Flag($climate, '-flag', 'Help text'))
            ->renderHelp()
        ;
    }

    public function testAddFlagWithExtraText(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->exactly(5))
            ->method('info')
            ->withConsecutive(['The title'], ['Usage:'], ['Commands:'], ['Flags:'], ['  -flag'])
        ;

        $climate
            ->expects($this->exactly(1))
            ->method('white')
            ->with('    Help text')
        ;

        $climate
            ->expects($this->exactly(1))
            ->method('out')
            ->with('    Extra text')
        ;

        (new Binary($climate, 'The title'))
            ->addFlag(new Flag($climate, '-flag', 'Help text', 'Extra text'))
            ->renderHelp()
        ;
    }
}
