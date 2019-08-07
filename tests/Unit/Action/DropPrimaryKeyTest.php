<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropPrimaryKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class DropPrimaryKeyTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropPrimaryKey(new Label('table_name'), new Label('table_name_pkey'));

        $this->assertSame('table_name_pkey', $action->getName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropPrimaryKey(new Label('table_name'), new Label('table_name_pkey')))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "table_name_pkey"',
            $queries[0],
        );
    }
}
