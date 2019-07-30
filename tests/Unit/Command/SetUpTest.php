<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Command;

use PeeHaa\Migres\Command\SetUp;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class SetUpTest extends TestCase
{
    public function tearDown(): void
    {
        @rmdir(DATA_DIRECTORY . '/setup-test');
        @unlink(dirname(dirname(DATA_DIRECTORY)) . '/migres.php');
    }

    public function testRun(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(9))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                'username',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunKeepsAskingForDatabaseName(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(10))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                '',
                'localhost',
                5432,
                'username',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabasePortAgainWhenInvalidPost(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
                )
        ;

        $climate
            ->expects($this->exactly(10))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                'a',
                5432,
                'username',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabaseUsernameAgainWhenNotProvided(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
                )
        ;

        $climate
            ->expects($this->exactly(10))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabasePasswordAgainWhenNotMatching(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
                )
        ;

        $climate
            ->expects($this->exactly(11))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                'username',
                'password',
                'passwordx',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForMigrationPathAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '1',
                DATA_DIRECTORY . '/setup-test',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForNamespaceAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '2',
                'PeeHaa\MigresTest\Data\SetupTest',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabaseNameAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '3',
                'test_db',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabaseHostAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '4',
                'localhost',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabasePortAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '5',
                '5432',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabaseUsernameAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(12))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '6',
                'username',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunAsksForDatabasePasswordAgainAfterVerifying(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(8))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(13))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                '',
                'username',
                'password',
                'password',
                '7',
                'password',
                'password',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunWhenDirectoryAlreadyExists(): void
    {
        mkdir(DATA_DIRECTORY . '/setup-test');

        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(6))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
            )
        ;

        $climate
            ->expects($this->exactly(9))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                'username',
                'password',
                'password',
                'y',
            )
        ;

        $command = new SetUp($climate);

        $command->run();
    }

    public function testRunValidateSettings(): void
    {
        $climate = $this->createMock(Climate::class);

        $climate
            ->method('input')
            ->willReturn($climate)
        ;

        $climate
            ->method('password')
            ->willReturn($climate)
        ;

        $climate
            ->method('defaultTo')
            ->willReturn($climate)
        ;

        $climate
            ->method('accept')
            ->willReturn($climate)
        ;

        $climate
            ->expects($this->exactly(7))
            ->method('info')
            ->withConsecutive(
                ['Migres - Configuration wizard'],
                ['Set up migrations'],
                ['Set up database connection'],
                ['Settings to be saved:'],
                ['Creating migration directory'],
                ['Writing settings to file ' . dirname(dirname(DATA_DIRECTORY)) . '/migres.php'],
                ['Initialization is finished'],
                )
        ;

        $climate
            ->expects($this->exactly(9))
            ->method('prompt')
            ->willReturnOnConsecutiveCalls(
                DATA_DIRECTORY . '/setup-test',
                'PeeHaa\MigresTest\Data\SetupTest',
                'test_db',
                'localhost',
                5432,
                'username',
                'password',
                'password',
                'y'
            )
        ;

        $command = new SetUp($climate);

        $command->run();

        $configuration = require dirname(dirname(DATA_DIRECTORY)) . '/migres.php';

        $this->assertSame(DATA_DIRECTORY . '/setup-test', $configuration['migrationPath']);
        $this->assertSame('PeeHaa\MigresTest\Data\SetupTest', $configuration['namespace']);
        $this->assertSame('test_db', $configuration['database']['name']);
        $this->assertSame('localhost', $configuration['database']['host']);
        $this->assertSame(5432, $configuration['database']['port']);
        $this->assertSame('username', $configuration['database']['username']);
        $this->assertSame('password', $configuration['database']['password']);
    }
}
