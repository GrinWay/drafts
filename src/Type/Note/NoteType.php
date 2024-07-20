<?php

namespace App\Type\Note;

//TODO : NoteType
class NoteType {
	public const NOTICE = 'notice';
	public const WARNING = 'warning';
	public const ERROR = 'error';
	
	public const TYPES = [
		'NOTICE'  => self::NOTICE,
		'WARNING' => self::WARNING,
		'ERROR'   => self::ERROR,
	];
	
	public const SNAKE_KEYS_TYPES = [
		'notice'  => self::NOTICE,
		'warning' => self::WARNING,
		'erorr'   => self::ERROR,
	];
}