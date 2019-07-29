<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropIndex;
use PHPUnit\Framework\TestCase;

class DropIndexTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropIndex('table_name', 'index_name');

        $this->assertSame('index_name', $action->getName());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropIndex('table_name', 'index_name'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'DROP INDEX "index_name"',
            $queries[0],
        );
    }
}
