<?php declare(strict_types=1);

namespace PeeHaa\Migres\Exception;

class InvalidMigrationPath extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('The migration path (`%s`) is invalid.', $path));
    }
}
