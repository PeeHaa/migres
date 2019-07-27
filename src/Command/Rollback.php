<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Log\Item;
use PeeHaa\Migres\Log\Migration as MigrationLog;

final class Rollback implements Command
{
    private Configuration $configuration;

    private \PDO $dbConnection;

    private Output $output;

    private MigrationLog $migrationLog;

    public function __construct(
        Configuration $configuration,
        \PDO $dbConnection,
        Output $output,
        MigrationLog $migrationLog
    ) {
        $this->configuration = $configuration;
        $this->dbConnection  = $dbConnection;
        $this->output        = $output;
        $this->migrationLog  = $migrationLog;
    }

    public function run(): void
    {
        try {
            /** @var Item $rollback */
            foreach ($this->migrationLog->getRollbacks() as $rollback) {
                $this->output->startRollback($rollback->getName());

                $this->dbConnection->beginTransaction();

                foreach ($rollback->getRollbackQueries() as $query) {
                    $this->output->runQuery($query);

                    $this->dbConnection->exec($query);
                }

                $this->migrationLog->removeEntry($rollback->getId());

                $this->dbConnection->commit();
            }
        } catch (\Throwable $e) {
            if ($this->dbConnection->inTransaction()) {
                $this->dbConnection->rollBack();
            }

            $this->output->error($e->getMessage());

            exit(1);
        }
    }
}
