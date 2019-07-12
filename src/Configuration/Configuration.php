<?php declare(strict_types=1);

namespace PeeHaa\Migres\Configuration;

use PeeHaa\Migres\Exception\InvalidMigrationPath;

final class Configuration
{
    private MigrationPath $migrationPath;

    private string $namespace;

    public function __construct(MigrationPath $migrationPath, string $namespace)
    {
        $this->migrationPath = $migrationPath;
        $this->namespace     = $namespace;
    }

    /**
     * @param array<string,string> $configuration
     * @throws InvalidMigrationPath
     */
    public static function fromArray(array $configuration): self
    {
        return new self(new MigrationPath($configuration['migrationPath']), $configuration['namespace']);
    }

    public function getMigrationPath(): string
    {
        return $this->migrationPath->getPath();
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
