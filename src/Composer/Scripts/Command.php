<?php

namespace App\Composer\Scripts;

use Composer\Plugin\CommandEvent;

class Command
{
    public static function execute(CommandEvent $event): void
    {
        echo __METHOD__ . \PHP_EOL;
        echo \sprintf('composer "%s" is executing...', $event->getCommandName()) . \PHP_EOL;
        /*
        \var_dump(get_debug_type($event));
        exit;
        */
    }
}
