<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Exception\InvalidFilename;
use PeeHaa\Migres\Migration;
use PeeHaa\Migres\Migration\MigrationActions;
use PeeHaa\Migres\MigrationSpecification;

final class Migrate implements Command
{
    private Configuration $configuration;

    private \PDO $dbConnection;

    public function __construct(Configuration $configuration, \PDO $dbConnection)
    {
        $this->configuration = $configuration;
        $this->dbConnection  = $dbConnection;
    }

    public function run(): void
    {
        try {
            /** @var Migration $migration */
            foreach ($this->getMigrations() as $migration) {
                echo sprintf('Starting migration: %s' . PHP_EOL, $migration->getName());

                foreach ($migration->getActions() as $tableActions) {
                    echo sprintf('Starting migration for table: %s' . PHP_EOL, $tableActions->getTableName());

                    $this->dbConnection->beginTransaction();

                    /** @var Action $action */
                    foreach ($tableActions->getActions() as $action) {
                        foreach ($action->toQueries($tableActions->getTableName()) as $query) {
                            echo sprintf('%s;' . PHP_EOL, $query);

                            $this->dbConnection->exec($query);
                        }
                    }

                    $this->dbConnection->commit();
                }
            }
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    private function getMigrations(): array
    {
        $migrations = [];

        foreach ($this->getFiles() as $filePath => $filename) {
            $migrations[] = new Migration(
                $this->getName($filename),
                $filePath,
                $this->getFullyQualifiedName($filename),
                $this->getTimestamp($filename),
                $this->getActions($filePath, $this->getFullyQualifiedName($filename)),
            );
        }

        return $migrations;
    }

    /**
     * @return array<string>
     */
    private function getFiles(): array
    {
        $migrationFiles = [];

        $fileSystemIteratorFlags = \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::SKIP_DOTS;

        /** @var \SplFileInfo $fileInfo */
        foreach (new \FilesystemIterator($this->configuration->getMigrationPath(), $fileSystemIteratorFlags) as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            if (!$this->isValidFile($fileInfo)) {
                continue;
            }

            $migrationFiles[$this->getFilePath($fileInfo)] = $fileInfo->getFilename();
        }

        ksort($migrationFiles, SORT_NATURAL);

        return $migrationFiles;
    }

    private function getName(string $filename): string
    {
        preg_match('~^\d{14}_(?P<className>[_a-z0-9]+)\.php$~', $filename, $matches);

        if (!isset($matches['className'])) {
            throw new InvalidFilename($filename);
        }

        $classNameParts = explode('_', $matches['className']);

        $classNameParts = array_map('ucfirst', $classNameParts);

        return implode('', $classNameParts);
    }

    private function getFullyQualifiedName(string $filename): string
    {
        return sprintf('%s\%s', $this->configuration->getNamespace(), $this->getName($filename));
    }

    private function isValidFile(\SplFileInfo $fileInfo): bool
    {
        return (bool) preg_match('~^\d{14}_[_a-z0-9]+\.php$~', $fileInfo->getFilename());
    }

    private function getFilePath(\SplFileInfo $fileInfo): string
    {
        return $fileInfo->getRealPath();
    }

    private function getTimestamp(string $filename): \DateTimeImmutable
    {
        preg_match('~^(?P<timestamp>\d{14})_[_a-z0-9]+\.php$~', $filename, $matches);

        if (!isset($matches['timestamp'])) {
            throw new InvalidFilename($filename);
        }

        return \DateTimeImmutable::createFromFormat('YmdHis', $matches['timestamp']);
    }

    private function getActions(string $filename, string $className): MigrationActions
    {
        require_once $filename;

        /** @var MigrationSpecification $migration */
        $migration = new $className();

        $migration->change();

        return $migration->up();
    }
}
