<?php declare(strict_types=1);

namespace PeeHaa\Migres\Cli;

use League\CLImate\CLImate;

final class Command
{
    private CLImate $climate;

    private string $command;

    private string $helpText;

    /** @var array<string> */
    private array $extraTexts;

    public function __construct(CLImate $climate, string $command, string $helpText, string ...$extraTexts)
    {
        $this->climate    = $climate;
        $this->command    = $command;
        $this->helpText   = $helpText;
        $this->extraTexts = $extraTexts;
    }

    public function render(): void
    {
        $this->climate->info('  ' . $this->command);
        $this->climate->br();
        $this->climate->white('    ' . $this->helpText);

        if ($this->extraTexts) {
            $this->climate->br();

            foreach ($this->extraTexts as $extraText) {
                $this->climate->out('    ' . $extraText);
            }
        }

        $this->climate->br();
        $this->climate->br();
    }
}
