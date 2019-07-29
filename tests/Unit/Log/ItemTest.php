<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Log;

use PeeHaa\Migres\Action\DropColumn;
use PeeHaa\Migres\Action\DropTable;
use PeeHaa\Migres\Log\Item;
use PeeHaa\Migres\Migration;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    private ?Item $item;

    public function setUp(): void
    {
        $this->item = Item::fromLogRecord([
            'id'                   => 'TheId',
            'name'                 => 'TheName',
            'filename'             => 'TheFilename',
            'fully_qualified_name' => 'TheFullyQualifiedName',
            'rollback_actions'     => json_encode(['SELECT 1', 'SELECT 2']),
            'created_at'           => '2019-03-26 12:36:18',
            'executed_at'          => '2019-03-28 14:21:28.984125',
        ]);
    }

    public function testGetId(): void
    {
        $this->assertSame('TheId', $this->item->getId());
    }

    public function testGetName(): void
    {
        $this->assertSame('TheName', $this->item->getName());
    }

    public function testGetFilename(): void
    {
        $this->assertSame('TheFilename', $this->item->getFilename());
    }

    public function testGetFullyQualifiedName(): void
    {
        $this->assertSame('TheFullyQualifiedName', $this->item->getFullyQualifiedName());
    }

    public function testGetRollbackQueries(): void
    {
        $this->assertCount(2, $this->item->getRollbackQueries());
        $this->assertSame('SELECT 1', $this->item->getRollbackQueries()[0]);
        $this->assertSame('SELECT 2', $this->item->getRollbackQueries()[1]);
    }

    public function testGetCreatedAt(): void
    {
        $this->assertSame('2019-03-26 12:36:18', $this->item->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testGetExecutedAt(): void
    {
        $this->assertSame('2019-03-28 14:21:28.984125', $this->item->getExecutedAt()->format('Y-m-d H:i:s.u'));
    }
    
    public function testFromMigration(): void 
    {
        $item = Item::fromMigration(
            new Migration('TheName', 'TheFilename', 'TheFullyQualifiedName', new \DateTimeImmutable('2019-03-26 12:36:18')),
            new DropColumn('table_name', 'column_name'),
            new DropTable('table_name'),
        );

        $this->assertRegExp('~^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i', $item->getId());
        $this->assertSame('TheName', $item->getName());
        $this->assertSame('TheFilename', $item->getFilename());
        $this->assertSame('TheFullyQualifiedName', $item->getFullyQualifiedName());
        $this->assertCount(2, $item->getRollbackQueries());
        $this->assertSame('ALTER TABLE "table_name" DROP COLUMN "column_name"', $item->getRollbackQueries()[0]);
        $this->assertSame('DROP TABLE "table_name"', $item->getRollbackQueries()[1]);
        $this->assertSame('2019-03-26 12:36:18', $item->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTimeImmutable::class, $item->getExecutedAt());
    }
    
    public function testFromLogRecord(): void 
    {
        $item = Item::fromLogRecord([
            'id'                   => 'TheId',
            'name'                 => 'TheName',
            'filename'             => 'TheFilename',
            'fully_qualified_name' => 'TheFullyQualifiedName',
            'rollback_actions'     => json_encode(['SELECT 1', 'SELECT 2']),
            'created_at'           => '2019-03-26 12:36:18',
            'executed_at'          => '2019-03-28 14:21:28.984125',
        ]);

        $this->assertSame('TheId', $item->getId());
        $this->assertSame('TheName', $item->getName());
        $this->assertSame('TheFilename', $item->getFilename());
        $this->assertSame('TheFullyQualifiedName', $item->getFullyQualifiedName());
        $this->assertCount(2, $item->getRollbackQueries());
        $this->assertSame('SELECT 1', $item->getRollbackQueries()[0]);
        $this->assertSame('SELECT 2', $item->getRollbackQueries()[1]);
        $this->assertSame('2019-03-26 12:36:18', $item->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertSame('2019-03-28 14:21:28.984125', $item->getExecutedAt()->format('Y-m-d H:i:s.u'));
    }
}
