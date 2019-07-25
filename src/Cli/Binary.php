<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Binary
{
    private CLImate $climate;

    private string $title;

    /** @var array<Usage> */
    private array $usages = [];

    /** @var array<Command> */
    private array $commands = [];

    public function __construct(CLImate $climate, string $title)
    {
        $this->climate = $climate;
        $this->title   = $title;
    }

    public function addUsage(Usage $usage): self
    {
        $this->usages[] = $usage;

        return $this;
    }

    public function addCommand(Command $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    public function renderHelp(): void
    {
        $this->climate->br();
        $this->climate->info($this->title);
        $this->climate->br();
        $this->climate->info('Usage:');
        $this->climate->br();

        array_map(fn(Usage $usage) => $usage->render(), $this->usages);

        $this->climate->br();
        $this->climate->info('Commands:');
        $this->climate->br();

        array_map(fn(Command $command) => $command->render(), $this->commands);
    }
}
