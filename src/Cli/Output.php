<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Output
{
    private CLImate $climate;

    private VerbosityLevel $verbosityLevel;

    public function __construct(CLImate $climate, VerbosityLevel $verbosityLevel)
    {
        $this->climate        = $climate;
        $this->verbosityLevel = $verbosityLevel;
    }

    public function startMigration(string $name): void
    {
        if (!$this->verbosityLevel->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_1)) {
            return;
        }

        $this->climate->br();
        $this->climate->info(sprintf('Running migration: %s', $name));
    }

    public function startRollback(string $name): void
    {
        if (!$this->verbosityLevel->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_1)) {
            return;
        }

        $this->climate->br();
        $this->climate->info(sprintf('Running rollback of: %s', $name));
    }

    public function startTableMigration(string $name): void
    {
        if (!$this->verbosityLevel->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_2)) {
            return;
        }

        $this->climate->br();
        $this->climate->info(sprintf('  Running migration for table: %s', $name));
    }

    public function runQuery(string $query): void
    {
        if (!$this->verbosityLevel->hasLevel(VerbosityLevel::VERBOSITY_LEVEL_3)) {
            return;
        }

        $this->climate->br();
        $this->climate->darkGray('  ' . $query);
    }

    public function error(string $message): void
    {
        $this->climate->br();
        $this->climate->error($message);
    }
}
