<?php declare(strict_types=1);

namespace PeeHaa\Migres\Command;

use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Configuration\Configuration;

final class CreateNewMigration implements Command
{
    private Configuration $configuration;

    private Output $output;

    private string $name;

    public function __construct(Configuration $configuration, Output $output, string $name)
    {
        $this->configuration = $configuration;
        $this->output        = $output;
        $this->name          = $name;
    }

    public function run(): void
    {
        $classParts = preg_split('/(?=[A-Z])/', $this->name, -1, PREG_SPLIT_NO_EMPTY);

        $classParts = array_map('strtolower', $classParts);

        $filename = sprintf(
            '%s/%s_%s.php',
            $this->configuration->getMigrationPath(),
            (new \DateTimeImmutable())->format('YmdHis'),
            implode('_', $classParts),
        );

        file_put_contents($filename, $this->getMigrationTemplate());

        $this->output->success(sprintf('Created new migration at %s', $filename));
    }

    private function getMigrationTemplate(): string
    {
        $template = file_get_contents(__DIR__ . '/../../template/new-migration.txt');

        return sprintf($template, $this->configuration->getNamespace(), $this->name);
    }
}
