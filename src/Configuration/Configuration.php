<?php declare(strict_types=1);

namespace PeeHaa\Migres\Configuration;

use PeeHaa\Migres\Exception\InvalidMigrationPath;

final class Configuration
{
    private MigrationPath $migrationPath;

    private string $namespace;

    private Database $databaseConfiguration;

    public function __construct(MigrationPath $migrationPath, string $namespace, Database $databaseConfiguration)
    {
        $this->migrationPath         = $migrationPath;
        $this->namespace             = $namespace;
        $this->databaseConfiguration = $databaseConfiguration;
    }

    /**
     * @param array<string,string> $configuration
     * @throws InvalidMigrationPath
     */
    public static function fromArray(array $configuration): self
    {
        return new self(
            new MigrationPath($configuration['migrationPath']),
            $configuration['namespace'],
            Database::fromArray($configuration['database']),
        );
    }

    public function getMigrationPath(): string
    {
        return $this->migrationPath->getPath();
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getDatabaseConfiguration(): Database
    {
        return $this->databaseConfiguration;
    }
}
