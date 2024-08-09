<?php

namespace App\Service;

use function Symfony\Component\String\u;

class WordUtil
{
	public static function countWords(?string $text): int {
		if (null === $text) {
			return 0;
		}
		
		return \count(\explode(' ', (string) u($text)->collapseWhitespace()));
	}
}
