<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Command
{
    private CLImate $climate;

    private string $command;

    private string $helpText;

    public function __construct(CLImate $climate, string $command, string $helpText)
    {
        $this->climate  = $climate;
        $this->command  = $command;
        $this->helpText = $helpText;
    }

    public function render(): void
    {
        $this->climate->info('  ' . $this->command);
        $this->climate->br();
        $this->climate->out('    ' . $this->helpText);
        $this->climate->br();
        $this->climate->br();
    }
}
