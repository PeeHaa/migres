<?php declare(strict_types=1);

namespace PeeHaa\Migres\Configuration;

final class Database
{
    private string $name;

    private string $host;

    private int $port;

    private string $username;

    private string $password;

    public function __construct(string $name, string $host, int $port, string $username, string $password)
    {
        $this->name     = $name;
        $this->host     = $host;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param array<string,string|int> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        return new self(
            $configuration['name'],
            $configuration['host'],
            $configuration['port'],
            $configuration['username'],
            $configuration['password'],
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
