<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Command;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Cli\VerbosityLevel;
use PeeHaa\Migres\Command\Rollback;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Configuration\Database;
use PeeHaa\Migres\Configuration\MigrationPath;
use PeeHaa\Migres\Log\Migration;
use PeeHaa\MigresTest\Fakes\Climate;
use PHPUnit\Framework\TestCase;

class RollbackTest extends TestCase
{
    public function testRun(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

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
                    'rollback_actions'     => '["SELECT 1", "SELECT2"]',
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

        $dbConnection
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('commit')
        ;

        $dbConnection
            ->expects($this->exactly(2))
            ->method('exec')
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

        $climate = $this->createMock(Climate::class);

        $output = new Output($climate, VerbosityLevel::fromCliArguments(['-q']));

        $command = new Rollback($configuration, $dbConnection, $output, new Migration($dbConnection));

        $command->run();
    }

    public function testRunRollsBackRollbackWhenInTransactionAndRethrows(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            ->expects($this->once())
            ->method('query')
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

        $command = new Rollback($configuration, $dbConnection, $output, new Migration($dbConnection));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Something went wrong!');

        $command->run();
    }

    public function testRunDoesNotRollbackWhenNotInTransactionAndRethrows(): void
    {
        $configuration = new Configuration(
            new MigrationPath(DATA_DIRECTORY . '/test-migrations'),
            'PeeHaa\MigresTest\Data\TestMigrations',
            new Database('test_db', 'localhost', 5432, 'username', 'password'),
        );

        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            ->expects($this->once())
            ->method('query')
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

        $command = new Rollback($configuration, $dbConnection, $output, new Migration($dbConnection));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Something went wrong!');

        $command->run();
    }
}
