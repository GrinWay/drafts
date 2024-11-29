<?php

namespace App\Monolog\Formatter;

use Monolog\LogRecord;
use Monolog\Formatter\LineFormatter;

class MailSentMarkLineFormatter extends LineFormatter
{
	public const SIMPLE_FORMAT = "[\033[0;36mMAIL\033[0m] |%level_name%| %channel%: %message% %extra%\n";
}