<?php declare(strict_types=1);

namespace PeeHaa\Migres\Log;

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
                name varchar(255) CONSTRAINT uniq_migres_log_name UNIQUE,
                created_at timestamp without time zone NOT NULL,
                executed_at timestamp without time zone NOT NULL,
                rollback_actions text NOT NULL,
                CONSTRAINT uniq_name_and_created_at UNIQUE (name, created_at)
            )
        ';

        $this->dbConnection->exec($sql);
    }

    public function write(Item $item): void
    {
        $sql = '
            INSERT INTO migres_log
                (id, name, created_at, executed_at, rollback_actions)
            VALUES
                (:id, :name, :created_at, :executed_at, :rollback_actions)
        ';

        $statement = $this->dbConnection->prepare($sql);
        $statement->execute([
            'id'               => $item->getId(),
            'name'             => $item->getName(),
            'created_at'       => $item->getCreatedAt()->format('Y-m-d H:i:s'),
            'executed_at'      => $item->getExecutedAt()->format('Y-m-d H:i:s'),
            'rollback_actions' => serialize($item->getRollbackActions()),
        ]);
    }
}
