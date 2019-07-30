<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Command;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Cli\VerbosityLevel;
use PeeHaa\Migres\Command\Migrate;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Configuration\Database;
use PeeHaa\Migres\Configuration\MigrationPath;
use PeeHaa\Migres\Log\Migration;
use PeeHaa\Migres\Retrospection\ColumnOptionsResolver;
use PeeHaa\Migres\Retrospection\DataTypeResolver;
use PeeHaa\Migres\Retrospection\Retrospector;
use PeeHaa\Migres\Retrospection\Sequence;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class MigrateTest extends TestCase
{
    public function testRun(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            // create log table (3) + table acounts (4)
            ->expects($this->exactly(7))
            ->method('exec')
        ;

        $dbConnection
            ->expects($this->exactly(2))
            ->method('beginTransaction')
        ;

        $dbConnection
            ->expects($this->exactly(2))
            ->method('commit')
        ;

        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->exactly(2))
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($statement)
        ;

        // get executed items
        $executedStatement = $this->createMock(\PDOStatement::class);

        $executedStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([])
        ;

        $dbConnection
            ->expects($this->once())
            ->method('query')
            ->willReturn($executedStatement)
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $command->run();
    }

    public function testRunRollsBackMigrationWhenInTransactionAndRethrows(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            ->expects($this->once())
            ->method('exec')
            ->willThrowException(new \Exception('Something went wrong!'))
        ;

        $dbConnection
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true)
        ;

        $dbConnection
            ->expects($this->once())
            ->method('rollBack')
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Something went wrong!');

        $command->run();
    }

    public function testRunDoesNotRollBackWhenNotInTransaction(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            ->expects($this->once())
            ->method('exec')
            ->willThrowException(new \Exception('Something went wrong!'))
        ;

        $dbConnection
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(false)
        ;

        $dbConnection
            ->expects($this->never())
            ->method('rollBack')
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Something went wrong!');

        $command->run();
    }

    public function testRunSkipsAlreadyExecutedMigrations(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            // create log table (3) + table acounts (2)
            ->expects($this->exactly(5))
            ->method('exec')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('commit')
        ;

        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        // get executed items
        $executedStatement = $this->createMock(\PDOStatement::class);

        $executedStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                [
                    'id'                   => '93ed4750-b559-400e-b8a5-9a1d9d2100c8',
                    'name'                 => 'SkipMigrationTest',
                    'filename'             => '/migrations/20190101100001_skip_migration_test.php',
                    'fully_qualified_name' => 'PeeHaa\MigresTest\Data\TestMigrations\SkipMigrationTest',
                    'rollback_actions'     => '[]',
                    'created_at'           => '2019-10-01 10:00:01',
                    'executed_at'          => '2019-10-01 12:00:01.24325',
                ],
            ])
        ;

        $dbConnection
            ->expects($this->once())
            ->method('query')
            ->willReturn($executedStatement)
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $command->run();
    }

    public function testRunSkipsDirectories(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations-with-directories'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            // create log table (3) + table acounts (2)
            ->expects($this->exactly(5))
            ->method('exec')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('commit')
        ;

        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        // get executed items
        $executedStatement = $this->createMock(\PDOStatement::class);

        $executedStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([])
        ;

        $dbConnection
            ->expects($this->once())
            ->method('query')
            ->willReturn($executedStatement)
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $command->run();
    }

    public function testRunSkipsInvalidFilenames(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations-with-invalid-filenames'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            // create log table (3) + table acounts (2)
            ->expects($this->exactly(5))
            ->method('exec')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('commit')
        ;

        $statement = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        // get executed items
        $executedStatement = $this->createMock(\PDOStatement::class);

        $executedStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([])
        ;

        $dbConnection
            ->expects($this->once())
            ->method('query')
            ->willReturn($executedStatement)
        ;

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Migrate(
            $configuration,
            $dbConnection,
            $output,
            new Migration($dbConnection),
            new Retrospector(
                $dbConnection,
                new DataTypeResolver(new Sequence()),
                new ColumnOptionsResolver(new Sequence()),
            ),
        );

        $command->run();
    }
}
