<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\DropCheck;
use PHPUnit\Framework\TestCase;

class DropCheckTest extends TestCase
{
    public function testGetName(): void
    {
        $action = new DropCheck('table_name', 'check_name');

        $this->assertSame('check_name', $action->getName());
    }

    public function testGetQueries(): void
    {
        $queries = (new DropCheck('table_name', 'check_name'))->toQueries();

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER TABLE "table_name" DROP CONSTRAINT "check_name"',
            $queries[0],
        );
    }
}
