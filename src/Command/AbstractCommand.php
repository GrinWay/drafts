<?php

namespace App\Command;

use GrinWay\Command\Command\AbstractCommand as GrinWayAbstractCommand;

abstract class AbstractCommand extends GrinWayAbstractCommand
{
	public const HELP = '!CHANGE_HELP!';
	public const DESCRIPTION = '!CHANGE_DESCRIPTION!';
	
    protected static function getCommandDescription(): string {
		return static::DESCRIPTION;
	}

    protected static function getCommandHelp(): string {
		return static::HELP;
	}
}