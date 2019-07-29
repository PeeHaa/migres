<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\VerbosityLevel;
use PHPUnit\Framework\TestCase;

class VerbosityLevelTest extends TestCase
{
    public function testConstructorThrowsOnOutOfRangeLevel(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(
            'Argument 1 passed to PeeHaa\Migres\Cli\VerbosityLevel::__construct must be one of: 0, 1, 2 or 3'
        );

        new VerbosityLevel(10);
    }

    public function testFromCliArgumentsWhenInSilentMode(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments(['-q']);

        $this->assertSame(0, $verbosityLevel->getLevel());
    }

    public function testFromCliArgumentsWhenInLevel1(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments(['-v']);

        $this->assertSame(1, $verbosityLevel->getLevel());
    }

    public function testFromCliArgumentsWhenInLevel2(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments(['-vv']);

        $this->assertSame(2, $verbosityLevel->getLevel());
    }

    public function testFromCliArgumentsWhenInLevel3(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments(['-vvv']);

        $this->assertSame(3, $verbosityLevel->getLevel());
    }

    public function testFromCliArgumentsWhenSilentModeOverrulesOtherModes(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments(['-v', '-q']);

        $this->assertSame(0, $verbosityLevel->getLevel());
    }

    public function testFromCliArgumentsDefaultsToLevel1WhenNotProvided(): void
    {
        $verbosityLevel = VerbosityLevel::fromCliArguments([]);

        $this->assertSame(1, $verbosityLevel->getLevel());
    }

    public function testHasLevelWhenLevelIsTooLow(): void
    {
        $this->assertFalse(
            (new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_1))->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_2),
        );
    }

    public function testHasLevelWhenLevelIsAndExactMatch(): void
    {
        $this->assertTrue(
            (new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_2))->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_2),
        );
    }

    public function testHasLevelWhenLevelIsHigher(): void
    {
        $this->assertTrue(
            (new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_3))->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_2),
        );
    }
}
