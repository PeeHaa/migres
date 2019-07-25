#!/usr/bin/env php
<?php declare(strict_types=1);

namespace PeeHaa\Migres\Bin;

use League\CLImate\CLImate;
use PeeHaa\Migres\Command\CreateNewMigration;
use PeeHaa\Migres\Command\Initialize;
use PeeHaa\Migres\Command\Migrate;
use PeeHaa\Migres\Command\Rollback;
use PeeHaa\Migres\Configuration\Configuration;
use PeeHaa\Migres\Retrospection\ColumnOptionsResolver;
use PeeHaa\Migres\Retrospection\DataTypeResolver;
use PeeHaa\Migres\Retrospection\Retrospector;
use PeeHaa\Migres\Retrospection\Sequence;

require_once __DIR__ . '/../vendor/autoload.php';

$supportedCommands = ['help', 'init', 'create', 'migrate', 'rollback'];

$climate = new CLImate();

if (!isset($argv[1]) || !in_array($argv[1], $supportedCommands) || $argv[1] === 'help') {
    $climate->br();

    $climate->info('Migres - The PostgreSQL migration tool');

    $climate->br();

    $climate->info('Usage:');

    $climate->out('migres init');
    $climate->out('migres create MyNewMigration');
    $climate->out('migres migrate [-t migration]');
    $climate->out('migres rollback [-t migration]');

    $climate->br();

    $climate->info('Commands:');
    $climate->out('    help, h');
    $climate->out('        Shows this information');
    $climate->out('    init');
    $climate->out('        Runs the configuration wizard');
    $climate->out('    create');
    $climate->out('        Creates a new migration');
    $climate->out('    migrate');
    $climate->out('        Runs migrations');
    $climate->out('    rollback');
    $climate->out('        Rolls back migrations');

    exit(1);
}

if ($argv[1] === 'init') {
    (new Initialize($climate))->run();

    exit(0);
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
