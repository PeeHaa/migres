<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use League\CLImate\CLImate;
use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Exception\InvalidFilename;
use PeeHaa\Migres\Migration;
use PeeHaa\Migres\Migration\MigrationActions;
use PeeHaa\Migres\Migration\Migrations;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Retrospection\Retrospector;

final class Migrate implements Command
{
    private Configuration $configuration;

    private \PDO $dbConnection;

    private Retrospector $retrospector;

    private CLImate $logger;

    public function __construct(Configuration $configuration, \PDO $dbConnection, Retrospector $retrospector, CLImate $logger)
    {
        $this->configuration = $configuration;
        $this->dbConnection  = $dbConnection;
        $this->retrospector  = $retrospector;
        $this->logger        = $logger;
    }

    public function run(): void
    {
        try {
            /** @var Migration $migration */
            foreach ($this->getMigrations() as $migration) {
                echo sprintf('Starting migration: %s' . PHP_EOL, $migration->getName());

                $migrationReversions = [];

                foreach ($migration->getActions() as $tableActions) {
                    echo sprintf('Starting migration for table: %s' . PHP_EOL, $tableActions->getTableName());

                    $this->dbConnection->beginTransaction();

                    /** @var Action $action */
                    foreach ($tableActions->getActions() as $action) {
                        $migrationReversions[] = $this->retrospector->getReverseAction($tableActions->getTableName(), $action);
                        //var_dump();

                        foreach ($action->toQueries($tableActions->getTableName()) as $query) {
                            $this->logger->info(sprintf('%s;' . PHP_EOL, $query));

                            $this->dbConnection->exec($query);
                        }
                    }

                    // store rollback actions
                    var_dump(array_reverse($migrationReversions));

                    $this->dbConnection->commit();
                }
            }
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    private function getMigrations(): Migrations
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

        return new Migrations(...$migrations);
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
