<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropIndex;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropIndexTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropIndex(new Label('table_name'), new Label('index_name'));

        $this->assertSame('index_name', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropIndex(new Label('table_name'), new Label('index_name')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'DROP INDEX "index_name"',
            $queries[0],
        );
    }
}
