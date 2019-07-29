<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Log;

use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Log\Item;
use PeeHaa\Migres\Log\Migration as Log;
use PeeHaa\Migres\Migration;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    public function testCreateTableWhenNotExists(): void
    {
        $dbConnection = $this->createMock(\PDO::class);

        $dbConnection
            ->expects($this->exactly(3))
            ->method('exec')
        ;

        (new Log($dbConnection))->createTableWhenNotExists();
    }

    public function testWrite(): void
    {
        $dbConnection = $this->createMock(\PDO::class);
        $statement    = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        (new Log($dbConnection))->write(Item::fromMigration(
            new Migration('TheName', 'TheFilename', 'TheFullyQualifiedName', new \DateTimeImmutable()),
            new DropColumn('table_name', 'column_name'),
            new DropTable('table_name'),
        ));
    }

    public function testGetExecutedItems(): void
    {
        $dbConnection = $this->createMock(\PDO::class);
        $statement    = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                [
                    'id'                   => 'TheId',
                    'name'                 => 'TheName',
                    'filename'             => 'TheFilename',
                    'fully_qualified_name' => 'TheFullyQualifiedName',
                    'rollback_actions'     => json_encode(['SELECT 1', 'SELECT 2']),
                    'created_at'           => '2019-03-26 12:36:18',
                    'executed_at'          => '2019-03-28 14:21:28.984125',
                ],
            ])
        ;

        $dbConnection
            ->expects($this->once())
            ->method('query')
            ->willReturn($statement)
        ;

        $executedItems = (new Log($dbConnection))->getExecutedItems();

        $this->assertCount(1, $executedItems);
        $this->assertInstanceOf(Item::class, $executedItems['TheFilename']);
    }

    public function testRemoveEntry(): void
    {
        $dbConnection = $this->createMock(\PDO::class);
        $statement    = $this->createMock(\PDOStatement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
        ;

        $dbConnection
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($statement)
        ;

        (new Log($dbConnection))->removeEntry('TheId');
    }
}
