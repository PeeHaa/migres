# Migres

The PostgreSQL migration tool

[![Latest Stable Version](https://poser.pugx.org/peehaa/migres/v/stable)](https://packagist.org/packages/peehaa/migres)
[![Build Status](https://travis-ci.org/PeeHaa/migres.svg?branch=master)](https://travis-ci.org/PeeHaa/migres)
[![Build status](https://ci.appveyor.com/api/projects/status/v5xbvaht1ovey7uh/branch/master?svg=true)](https://ci.appveyor.com/project/PeeHaa/migres/branch/master)
[![Coverage Status](https://coveralls.io/repos/github/PeeHaa/migres/badge.svg?branch=master)](https://coveralls.io/github/PeeHaa/migres?branch=master)
[![License](https://poser.pugx.org/peehaa/migres/license)](https://packagist.org/packages/peehaa/migres)

## Requirements

- PHP 7.4
- PostgreSQL 9.5

## Usage

Note: this is alpha software. Do not use in production (yet).

*__Warning: never allow untrusted input in table specifications as all migrations are translated to raw SQL!__*

- Add the project using composer `composer install peehaa/migres`
- Run the setup `./vendor/bin/migres setup`
- Run without arguments to view the available commands `./vendor/bin/migres setup`

All native PostgreSQL data types are implemented and the list can be found at: https://github.com/PeeHaa/migres/tree/master/src/DataType

### TOC

- [Creating a table](#creating-a-table)
- [Renaming a table](#renaming-a-table)
- [Dropping a table](#dropping-a-table)
- [Table methods](#table-methods)
  - [addColumn](#tableaddcolumnstring-name-migresdatatypetype-datatype)
  - [dropColumn](#tabledropcolumnstring-name)
  - [renameColumn](#tablerenamecolumnstring-oldname-string-newname)
  - [changeColumn](#tablechangecolumnstring-name-migresdatatypetype-datatype)
  - [primaryKey](#tableprimarykeystring-column-string-columns)
  - [namedPrimaryKey](#tablenamedprimarykeystring-name-string-column-string-columns)
  - [dropPrimaryKey](#tabledropprimarykeystring-name)
  - [renamePrimaryKey](#tablerenameprimarykeystring-oldname-string-newname)
  - [addUniqueConstraint](#tableadduniqueconstraintstring-constraintname-string-column-string-columns)
  - [dropUniqueConstraint](#tabledropuniqueconstraintstring-constraintname)
  - [addIndex](#tableaddindexstring-indexname-string-column-string-columns)
  - [addBtreeIndex](#tableaddbtreeindexstring-indexname-string-column-string-columns)
  - [addHashIndex](#tableaddhashindexstring-indexname-string-column-string-columns)
  - [addGistIndex](#tableaddgistindexstring-indexname-string-column-string-columns)
  - [addGinIndex](#tableaddginindexstring-indexname-string-column-string-columns)
  - [dropIndex](#tabledropindexstring-indexname)
  - [addCheck](#tableaddcheckstring-checkname-string-expression)
  - [dropCheck](#tabledropcheckstring-checkname)

### Creating a table

```php
<?php declare(strict_types=1);

namespace Vendor\Migrations;

use PeeHaa\Migres\DataType\BigSerial;
use PeeHaa\Migres\DataType\Boolean;
use PeeHaa\Migres\DataType\CharacterVarying;
use PeeHaa\Migres\MigrationSpecification;
use PeeHaa\Migres\Specification\Table;

class CreateTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->createTable('users', function (Table $table) {
            $table->addColumn('id', new BigSerial());
            $table->addColumn('is_admin', new Boolean())->notNull()->default(false);
            $table->addColumn('name', new CharacterVarying(128))->notNull();
            $table->addColumn('email_address', new CharacterVarying(255))->notNull();
            $table->primaryKey('id');
            $table->addIndex('users_name', 'name');
            $table->addUniqueConstraint('email_address_unq', 'email_address');
        });
    }
}
```

### Renaming a table

```php
<?php declare(strict_types=1);

namespace Vendor\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->renameTable('users', 'members');
    }
}
```

### Dropping a table

```php
<?php declare(strict_types=1);

namespace Vendor\Migrations;

use PeeHaa\Migres\MigrationSpecification;

class RenameTable extends MigrationSpecification
{
    public function change(): void
    {
        $this->dropTable('members');
    }
}
```

### Table methods

The table object defines the following methods:

#### `Table::addColumn(string $name, \Migres\DataType\Type $dataType)`

```php
$table->addColumn('column_name', new Integer());
```

```php
$table->addColumn('column_name', new Integer())->notNull;
```

```php
$table->addColumn('column_name', new Integer())->default(12);
```

#### `Table::dropColumn(string $name)`

```php
$table->dropColumn('column_name');
```

#### `Table::renameColumn(string $oldName, string $newName)`

```php
$table->renameColumn('old_name', 'new_name');
```

#### `Table::changeColumn(string $name, \Migres\DataType\Type $dataType)`

```php
$table->changeColumn('column_name', new IntegerType());
```

```php
$table->changeColumn('column_name', new IntegerType())->notNull();
```

```php
$table->changeColumn('column_name', new IntegerType())->default(12);
```

#### `Table::primaryKey(string $column, [string ...$columns])`

```php
$table->primaryKey('column_name');
```

```php
$table->primaryKey('column_name1', 'column_name2');
```

#### `Table::dropPrimaryKey([string $name])`

```php
$table->dropPrimaryKey();
```

```php
$table->dropPrimaryKey('table_name_pkey');
```

#### `Table::namedPrimaryKey(string $name, string $column, [string ...$columns])`

```php
$table->namedPrimaryKey('custom_name_pkey', 'column_name');
```

```php
$table->namedPrimaryKey('custom_name_pkey', 'column_name1', 'column_name2');
```

#### `Table::renamePrimaryKey(string $oldName, string $newName)`

```php
$table->renamePrimaryKey('old_name', 'new_name');
```

#### `Table::addUniqueConstraint(string $constraintName, string $column, [string ...$columns])`

```php
$table->addUniqueConstraint('constraint_name', 'column_name');
```

```php
$table->addUniqueConstraint('constraint_name', 'column_name1', 'column_name2');
```

#### `Table::dropUniqueConstraint(string $constraintName)`

```php
$table->dropUniqueConstraint('constraint_name');
```

#### `Table::addIndex(string $indexName, string $column, [string ...$columns])`

```php
$table->addIndex('name_idx', 'column_name');
```

```php
$table->addIndex('name_idx', 'column_name DESC');
```

```php
$table->addIndex('name_idx', 'column_name1 DESC', 'column_name2 DESC');
```

#### `Table::addBtreeIndex(string $indexName, string $column, [string ...$columns])`

```php
$table->addBtreeIndex('name_idx', 'column_name');
```

```php
$table->addBtreeIndex('name_idx', 'column_name DESC');
```

```php
$table->addBtreeIndex('name_idx', 'column_name1 DESC', 'column_name2 DESC');
```

#### `Table::addHashIndex(string $indexName, string $column, [string ...$columns])`

```php
$table->addHashIndex('name_idx', 'column_name');
```

```php
$table->addHashIndex('name_idx', 'column_name DESC');
```

```php
$table->addHashIndex('name_idx', 'column_name1 DESC', 'column_name2 DESC');
```

#### `Table::addGistIndex(string $indexName, string $column, [string ...$columns])`

```php
$table->addGistIndex('name_idx', 'column_name');
```

```php
$table->addGistIndex('name_idx', 'column_name DESC');
```

```php
$table->addGistIndex('name_idx', 'column_name1 DESC', 'column_name2 DESC');
```

#### `Table::addGinIndex(string $indexName, string $column, [string ...$columns])`

```php
$table->addGinIndex('name_idx', 'column_name');
```

```php
$table->addGinIndex('name_idx', 'column_name DESC');
```

```php
$table->addGinIndex('name_idx', 'column_name1 DESC', 'column_name2 DESC');
```

#### `Table::dropIndex(string $indexName)`

```php
$table->dropIndex('name_idx');
```

#### `Table::addCheck(string $checkName, string $expression)`

```php
$table->addCheck('bigger_than_10_chk', 'column_name > 10');
```

#### `Table::dropCheck(string $checkName)`

```php
$table->dropCheck('bigger_than_10_chk');
```

### Command line

#### Setup

```shell script
./vendor/bin/migres setup
```

This will run the setup wizard which guides you through the process of setting up the configuration.

#### Create new migration

```shell script
./vendor/bin/migres create NewMigrationName
```

This will create a new migration and writes the file to the migrations directory.

#### Run migrations

```shell script
./vendor/bin/migres migrate [-v[v][v]]
```

Run the migrations

#### Run migrations

```shell script
./vendor/bin/migres rollback [-v[v][v]]
```

Rolls back the migrations
