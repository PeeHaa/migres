<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Cli;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Cli\VerbosityLevel;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function testStartMigration(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('Running migration: MigrationName')
        ;

        (new Output($climate, new VerbosityLevel()))->startMigration('MigrationName');
    }

    public function testStartMigrationBailsOutWhenVerbosityLevelIsnNotBeingMet(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->never())
            ->method('br')
        ;

        $climate
            ->expects($this->never())
            ->method('info')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_0)))->startMigration('MigrationName');
    }

    public function testStartRollback(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('Running rollback of: MigrationName')
        ;

        (new Output($climate, new VerbosityLevel()))->startRollback('MigrationName');
    }

    public function testStartRollbackBailsOutWhenVerbosityLevelIsnNotBeingMet(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->never())
            ->method('br')
        ;

        $climate
            ->expects($this->never())
            ->method('info')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_0)))->startRollback('MigrationName');
    }

    public function testStartTableMigration(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('info')
            ->with('  Running migration for table: table_name')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_2)))->startTableMigration('table_name');
    }

    public function testStartTableMigrationBailsOutWhenVerbosityLevelIsnNotBeingMet(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->never())
            ->method('br')
        ;

        $climate
            ->expects($this->never())
            ->method('info')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_0)))->startTableMigration('table_name');
        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_1)))->startTableMigration('table_name');
    }

    public function testRunQuery(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('darkGray')
            ->with('  SELECT 1')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_3)))->runQuery('SELECT 1');
    }

    public function testRunQueryBailsOutWhenVerbosityLevelIsnNotBeingMet(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->never())
            ->method('br')
        ;

        $climate
            ->expects($this->never())
            ->method('darkGray')
        ;

        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_0)))->runQuery('SELECT 1');
        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_1)))->runQuery('SELECT 1');
        (new Output($climate, new VerbosityLevel(VerbosityLevel::VERBOSITY_LEVEL_2)))->runQuery('SELECT 1');
    }

    public function testSuccess(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('lightGreen')
        ;

        (new Output($climate, new VerbosityLevel()))->success('success!');
    }

    public function testError(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->expects($this->once())
            ->method('br')
        ;

        $climate
            ->expects($this->once())
            ->method('error')
        ;

        (new Output($climate, new VerbosityLevel()))->error('error!');
    }
}
