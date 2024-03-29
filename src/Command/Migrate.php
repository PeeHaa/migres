<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use League\CLImate\CLImate;
use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Exception\InvalidFilename;
use PeeHaa\Migres\Log\Item;
use PeeHaa\Migres\Log\Migration as MigrationLog;
use PeeHaa\Migres\Migration;
use PeeHaa\Migres\Migration\Migrations;
use PeeHaa\Migres\Migration\TableActions;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Retrospection\Retrospector;

final class Migrate implements Command
{
    private Configuration $configuration;

    private \PDO $dbConnection;

    private Output $output;

    private MigrationLog $migrationLog;

    private Retrospector $retrospector;

    public function __construct(
        Configuration $configuration,
        \PDO $dbConnection,
        Output $output,
        MigrationLog $migrationLog,
        Retrospector $retrospector
    ) {
        $this->configuration = $configuration;
        $this->dbConnection  = $dbConnection;
        $this->output        = $output;
        $this->migrationLog  = $migrationLog;
        $this->retrospector  = $retrospector;
    }

    public function run(): void
    {
        try {
            $this->migrationLog->createTableWhenNotExists();

            /** @var Migration $migration */
            foreach ($this->getMigrations() as $migration) {
                $this->output->startMigration($migration->getName());

                $migrationReversions = [];

                $this->dbConnection->beginTransaction();

                /** @var TableActions $tableActions */
                foreach ($migration->getActions() as $tableActions) {
                    $this->output->startTableMigration($tableActions->getName());

                    foreach ($tableActions as $tableAction) {
                        $migrationReversions[] = $this->retrospector->getReverseAction($tableAction);

                        foreach ($tableAction->toQueries() as $query) {
                            $this->output->runQuery($query);

                            $this->dbConnection->exec($query);
                        }
                    }
                }

                $this->migrationLog->write(Item::fromMigration($migration, ...array_reverse($migrationReversions)));

                $this->dbConnection->commit();
            }
        } catch (\Throwable $e) {
            if ($this->dbConnection->inTransaction()) {
                $this->dbConnection->rollBack();
            }

            throw $e;
        }

        $this->output->success('Successfully performed all migrations!');
    }

    private function getMigrations(): Migrations
    {
        $executedMigrations = $this->migrationLog->getExecutedItems();

        $migrations = [];

        foreach ($this->getFiles() as $filePath => $filename) {
            if (array_key_exists($filename, $executedMigrations)) {
                continue;
            }

            $migrations[] = new Migration(
                $this->getName($filename),
                $filePath,
                $this->getFullyQualifiedName($filename),
                $this->getTimestamp($filename),
                ...$this->getActions($filePath, $this->getFullyQualifiedName($filename)),
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

        return \DateTimeImmutable::createFromFormat('YmdHis', $matches['timestamp']);
    }

    /**
     * @return array<TableActions>
     */
    private function getActions(string $filename, string $className): array
    {
        require_once $filename;

        /** @var MigrationSpecification $migration */
        $migration = new $className();

        $migration->change();

        return $migration->getMigrationSteps();
    }
}
