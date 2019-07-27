#!/usr/bin/env php
<?php declare(strict_types=1);

namespace PeeHaa\Migres\Bin;

use League\CLImate\CLImate;
use PeeHaa\Migres\Cli\Binary;
use PeeHaa\Migres\Cli\Command;
use PeeHaa\Migres\Cli\Output;
use PeeHaa\Migres\Cli\Usage;
use PeeHaa\Migres\Cli\VerbosityLevel;
use PeeHaa\Migres\Command\CreateNewMigration;
use PeeHaa\Migres\Command\SetUp;
use PeeHaa\Migres\Command\Migrate;
use PeeHaa\Migres\Command\Rollback;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Configuration\Validator;
use PeeHaa\Migres\Log\Migration as MigrationLog;
use PeeHaa\Migres\Retrospection\ColumnOptionsResolver;
use PeeHaa\Migres\Retrospection\DataTypeResolver;
use PeeHaa\Migres\Retrospection\Retrospector;
use PeeHaa\Migres\Retrospection\Sequence;

require_once __DIR__ . '/../vendor/autoload.php';

$supportedCommands = ['help', 'setup', 'create', 'migrate', 'rollback'];

$climate = new CLImate();

if (!isset($argv[1]) || in_array($argv[1], ['help', '-h', '--help'], true) || !in_array($argv[1], $supportedCommands)) {
    $binary = (new Binary($climate, 'Migres - The PostgreSQL migration tool'))
        ->addUsage(new Usage($climate, 'migres setup'))
        ->addUsage(new Usage($climate, 'migres create MyNewMigration'))
        ->addUsage(new Usage($climate, 'migres migrate [-t migration]'))
        ->addUsage(new Usage($climate, 'migres rollback [-t migration]'))
        ->addCommand(new Command($climate, 'help', 'Shows this help information'))
        ->addCommand(new Command($climate, 'setup', 'Runs the configuration wizard'))
        ->addCommand(new Command($climate, 'create', 'Creates a new migration'))
        ->addCommand(new Command($climate, 'migrate', 'Runs migrations'))
        ->addCommand(new Command($climate, 'rollback', 'Rolls back migrations'))
    ;

    $binary->renderHelp();

    exit(0);
}

if ($argv[1] === 'setup') {
    (new SetUp($climate))->run();

    exit(0);
}

if (!(new Validator(getcwd() . '/migres.php'))->validate()) {
    $climate->br();
    $climate->error('Configuration not found or invalid.');
    $climate->br();
    $climate->out('Please run <dark_gray>migres init</dark_gray> to fix your configuration');

    exit(1);
}

if ($argv[1] === 'create') {
    (new CreateNewMigration(
        Configuration::fromArray(require getcwd() . '/migres.php'),
        $argv[2],
    ))->run();

    exit(0);
}

$dbConnection = createDatabaseConnectionFromConfiguration(require getcwd() . '/migres.php');

if ($argv[1] === 'migrate') {
    (new Migrate(
        Configuration::fromArray(require getcwd() . '/migres.php'),
        $dbConnection,
        new Output($climate, VerbosityLevel::fromCliArguments($argv)),
        new MigrationLog($dbConnection),
        new Retrospector($dbConnection, new DataTypeResolver(new Sequence()), new ColumnOptionsResolver(new Sequence())),
        $climate,
    ))->run();

    exit(0);
}

if ($argv[1] === 'rollback') {
    (new Rollback(
        Configuration::fromArray(require getcwd() . '/migres.php'),
        createDatabaseConnectionFromConfiguration(require getcwd() . '/migres.php'),
    ))->run();

    exit(0);
}

function createDatabaseConnectionFromConfiguration(array $configuration): \PDO
{
    $dsn = sprintf(
        'pgsql:dbname=%s;host=%s;port=%s',
        $configuration['database']['name'],
        $configuration['database']['host'],
        $configuration['database']['port']
    );

    $dbConnection = new \PDO($dsn, $configuration['database']['username'], $configuration['database']['password']);

    $dbConnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    $dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $dbConnection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    return $dbConnection;
}
