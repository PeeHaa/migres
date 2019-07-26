<?php declare(strict_types=1);

namespace PeeHaa\Migres\Configuration;

final class Validator
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function validate(): bool
    {
        if (!file_exists($this->filename)) {
            return false;
        }

        $configuration = require $this->filename;

        if (!$this->validateConfigurationKeys($configuration)) {
            return false;
        }

        return true;
    }

    private function validateConfigurationKeys(array $configuration): bool
    {
        foreach (['migrationPath', 'namespace', 'database'] as $requiredKey) {
            if (!array_key_exists($requiredKey, $configuration)) {
                return false;
            }
        }

        return true;
    }
}
