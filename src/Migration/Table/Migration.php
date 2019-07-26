<?php declare(strict_types=1);

namespace PeeHaa\Migres\Migration\Table;

use PeeHaa\Migres\Migration\Actions;

interface Migration
{
    public function getActions(): Actions;
}
