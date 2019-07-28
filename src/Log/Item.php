<?php declare(strict_types=1);

namespace PeeHaa\Migres\Log;

use PeeHaa\Migres\Action\Action;
use PeeHaa\Migres\Action_old\ReverseAction;
use PeeHaa\Migres\Migration;

final class Item
{
    private string $id;

    private string $name;

    private string $filename;

    private string $fullyQualifiedName;

    /** @var array<int,string> */
    private array $rollbackQueries;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $executedAt;

    private function __construct(
        string $id,
        string $name,
        string $filename,
        string $fullyQualifiedName,
        array $rollbackQueries,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $executedAt
    ) {
        $this->id                 = $id;
        $this->name               = $name;
        $this->filename           = $filename;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->rollbackQueries    = $rollbackQueries;
        $this->createdAt          = $createdAt;
        $this->executedAt         = $executedAt;
    }

    public static function fromMigration(Migration $migration, Action ...$rollbackActions): self
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        $id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

        $queries = [];

        foreach ($rollbackActions as $rollbackAction) {
            foreach ($rollbackAction->toQueries() as $query) {
                $queries[] = $query;
            }
        }

        return new self(
            $id,
            $migration->getName(),
            $migration->getFilename(),
            $migration->getFullyQualifiedName(),
            $queries,
            $migration->getTimestamp(),
            new \DateTimeImmutable(),
        );
    }

    /**
     * @param array<string,mixed> $logRecord
     */
    public static function fromLogRecord(array $logRecord): self
    {
        return new self(
            $logRecord['id'],
            $logRecord['name'],
            $logRecord['filename'],
            $logRecord['fully_qualified_name'],
            json_decode($logRecord['rollback_actions'], true),
            new \DateTimeImmutable($logRecord['created_at']),
            new \DateTimeImmutable($logRecord['executed_at']),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }

    /**
     * @return array<int,string>
     */
    public function getRollbackQueries(): array
    {
        return $this->rollbackQueries;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getExecutedAt(): \DateTimeImmutable
    {
        return $this->executedAt;
    }
}
