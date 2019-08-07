<?php declare(strict_types=1);

namespace PeeHaa\MigresTest\Unit\Action;

use PeeHaa\Migres\Action\RenamePrimaryKey;
use PeeHaa\Migres\Specification\Label;
use PHPUnit\Framework\TestCase;

class RenamePrimaryKeyTest extends TestCase
{
    public function testGetOldName(): void
    {
        $action = new RenamePrimaryKey(new Label('table_name'), new Label('old_name_pkey'), new Label('new_name_pkey'));

        $this->assertSame('old_name_pkey', $action->getOldName()->toString());
    }

    public function testGetNewName(): void
    {
        $action = new RenamePrimaryKey(new Label('table_name'), new Label('old_name_pkey'), new Label('new_name_pkey'));

        $this->assertSame('new_name_pkey', $action->getNewName()->toString());
    }

    public function testGetQueries(): void
    {
        $queries = (new RenamePrimaryKey(new Label('table_name'), new Label('old_name_pkey'), new Label('new_name_pkey')))
            ->toQueries()
        ;

        $queries = iterator_to_array($queries);

        $this->assertCount(1, $queries);

        $this->assertSame(
            'ALTER INDEX "old_name_pkey" RENAME TO "new_name_pkey"',
            $queries[0],
        );
    }
}
