<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Migration;

use PeeHaa\Migres\Migration\Queries;
use PHPUnit\Framework\TestCase;

class QueriesTest extends TestCase
{
    public function testIteratorImplementation(): void
    {
        $queries = new Queries(
            'SELECT 1',
            'SELECT 2',
        );

        foreach ($queries as $i => $query) {
            $this->assertSame('SELECT ' . ($i + 1), $query);
        }
    }
}
