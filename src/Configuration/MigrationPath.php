<?php declare(strict_types=1);

namespace PeeHaa\Migres\Configuration;

use PeeHaa\Migres\Exception\InvalidMigrationPath;

class MigrationPath
{
    private string $path;

    public function __construct(string $path)
    {
        if (!is_dir($path)) {
            throw new InvalidMigrationPath($path);
        }

        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
