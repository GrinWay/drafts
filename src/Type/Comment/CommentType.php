<?php

namespace App\Type\Comment;

class CommentType
{
	// Step 2 (add)
	public const ALL = [
		'CINEMA'          => self::CINEMA,
		'RECIPE'          => self::RECIPE,
		'COMMENT'         => self::COMMENT,
		'SHORT'           => self::SHORT,
	];
	
	// Step 1 (add)
	public const CINEMA   = 'cinema';
	public const RECIPE   = 'recipe';
	public const COMMENT  = 'comment';
	public const SHORT    = 'short';
}