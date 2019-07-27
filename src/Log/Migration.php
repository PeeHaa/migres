<?php declare(strict_types=1);

namespace PeeHaa\Migres\Log;

use PeeHaa\Migres\Rollback;

final class Migration
{
    private \PDO $dbConnection;

    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createTableWhenNotExists(): void
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS "migres_log" (
                id uuid CONSTRAINT pk_migres_log PRIMARY KEY,
                name character varying(255) NOT NULL CONSTRAINT uniq_migres_log_name UNIQUE,
                filename character varying (255) NOT NULL CONSTRAINT uniq_migres_log_filename UNIQUE,
                fully_qualified_name character varying (255) NOT NULL CONSTRAINT uniq_migres_log_fqn UNIQUE,
                rollback_actions jsonb NOT NULL,
                created_at timestamp without time zone NOT NULL,
                executed_at timestamp without time zone NOT NULL DEFAULT NOW(),
                CONSTRAINT uniq_name_and_created_at UNIQUE (name, created_at)
            )
        ';

        $this->dbConnection->exec($sql);

        $this->dbConnection->exec('CREATE INDEX IF NOT EXISTS idx_migres_log_executed_at_asc ON migres_log(executed_at ASC)');
        $this->dbConnection->exec('CREATE INDEX IF NOT EXISTS idx_migres_log_executed_at_desc ON migres_log(executed_at DESC)');
    }

    public function write(Item $item): void
    {
        $sql = '
            INSERT INTO migres_log
                (id, name, filename, fully_qualified_name, rollback_actions, created_at, executed_at)
            VALUES
                (:id, :name, :filename, :fullyQualifiedName, :rollbackActions, :createdAt, :executedAt)
        ';

        $statement = $this->dbConnection->prepare($sql);
        $statement->execute([
            'id'                 => $item->getId(),
            'name'               => $item->getName(),
            'filename'           => $item->getFilename(),
            'fullyQualifiedName' => $item->getFullyQualifiedName(),
            'rollbackActions'    => json_encode($item->getRollbackQueries()),
            'createdAt'          => $item->getCreatedAt()->format('Y-m-d H:i:s'),
            'executedAt'         => $item->getExecutedAt()->format('Y-m-d H:i:s.u'),
        ]);
    }

    /**
     * @return array<Item>
     */
    public function getExecutedItems(): array
    {
        $sql = '
            SELECT id, name, filename, fully_qualified_name, rollback_actions, created_at, executed_at
            FROM migres_log
            ORDER BY executed_at DESC
        ';

        $statement = $this->dbConnection->query($sql);

        $rollbacks = [];

        foreach ($statement->fetchAll() as $record) {
            $rollbacks[basename($record['filename'])] = Item::fromLogRecord($record);
        }

        return $rollbacks;
    }

    public function removeEntry(string $id): void
    {
        $sql = '
            DELETE FROM migres_log
            WHERE id = :id
        ';

        $statement = $this->dbConnection->prepare($sql);

        $statement->execute([
            'id' => $id,
        ]);
    }
}
