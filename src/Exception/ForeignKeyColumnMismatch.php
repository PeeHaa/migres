<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

final class ForeignKeyColumnMismatch extends Exception
{
    public function __construct(int $columnCount, int $referencedColumnCount)
    {
        parent::__construct(
            sprintf(
                'Column count in foreign key constraint (%d) must match referenced column count (%d)',
                $columnCount,
                $referencedColumnCount,
            ),
        );
    }
}
