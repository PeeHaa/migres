<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropForeignKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropForeignKeyTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropForeignKey(new Label('table_name'), new Label('name_fkey'));

        $this->assertSame('name_fkey', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropForeignKey(new Label('table_name'), new Label('name_fkey')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "name_fkey"',
            $queries[0],
        );
    }
}
