<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use League\CLImate\CLImate;
use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Exception\InvalidFilename;
use PeeHaa\Migres\Log\Item;
use PeeHaa\Migres\Migration;
use PeeHaa\Migres\Log\Migration as MigrationLog;
use PeeHaa\Migres\Migration\MigrationActions;
use PeeHaa\Migres\Migration\Migrations;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Retrospection\Retrospector;

final class Migrate implements Command
{
    private Configuration $configuration;

    private \PDO $dbConnection;

    private Output $output;

    private MigrationLog $migrationLog;

    private Retrospector $retrospector;

    private CLImate $logger;

    public function __construct(
        Configuration $configuration,
        \PDO $dbConnection,
        Output $output,
        MigrationLog $migrationLog,
        Retrospector $retrospector,
        CLImate $logger
    ) {
        $this->configuration = $configuration;
        $this->dbConnection  = $dbConnection;
        $this->output        = $output;
        $this->migrationLog  = $migrationLog;
        $this->retrospector  = $retrospector;
        $this->logger        = $logger;
    }

    public function run(): void
    {
        try {
            $this->migrationLog->createTableWhenNotExists();

            /** @var Migration $migration */
            foreach ($this->getMigrations() as $migration) {
                $this->output->startMigration($migration->getName());

                $migrationReversions = [];

                foreach ($migration->getActions() as $tableActions) {
                    $this->output->startTableMigration($tableActions->getTableName());

                    $this->dbConnection->beginTransaction();

                    /** @var Action $action */
                    foreach ($tableActions as $action) {
                        $migrationReversions[] = $this->retrospector->getReverseAction($tableActions->getTableName(), $action);

                        foreach ($action->toQueries($tableActions->getTableName()) as $query) {
                            $this->output->runQuery($query);

                            $this->dbConnection->exec($query);
                        }
                    }

                    $this->migrationLog->write(Item::fromMigration($migration, ...array_reverse($migrationReversions)));

                    $this->dbConnection->commit();
                }
            }
        } catch (\Throwable $e) {
            if ($this->dbConnection->inTransaction()) {
                $this->dbConnection->rollBack();
            }

            $this->output->error($e->getMessage());

            exit(1);
        }
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

        return $migration->getActions();
    }
}
