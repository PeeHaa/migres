<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use PeeHaa\Migres\Configuration\Configuration;

final class Rollback implements Command
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
            /** @var \PeeHaa\Migres\Rollback $rollback */
            foreach ($this->getRollbacks() as $rollback) {
                echo sprintf('Starting rollback of: %s' . PHP_EOL, $rollback->getName());

                $this->dbConnection->beginTransaction();

                foreach ($rollback->getQueries() as $query) {

                    echo sprintf('%s;' . PHP_EOL, $query);

                    $this->dbConnection->exec($query);
                }

                $this->removeLogEntry($rollback);

                $this->dbConnection->commit();
            }
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * @return array<\PeeHaa\Migres\Rollback>
     */
    private function getRollbacks(): array
    {
        $sql = '
            SELECT id, name, filename, fully_qualified_name, timestamp, execution, rollback_actions
            FROM migres_log
            ORDER BY id DESC
        ';

        $statement = $this->dbConnection->query($sql);

        $rollbacks = [];

        foreach ($statement->fetchAll() as $migration) {
            $rollbacks[] = \PeeHaa\Migres\Rollback::fromLogRecord($migration);
        }

        return $rollbacks;
    }

    private function removeLogEntry(\PeeHaa\Migres\Rollback $rollback): void
    {
        $sql = '
            DELETE FROM migres_log
            WHERE id = :id
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'id' => $rollback->getId(),
        ]);
    }
}
