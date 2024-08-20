<?php

namespace App\Service;

use function Symfony\Component\String\u;

class WordUtil
{
	public static function countWords(?string $text): int {
		$text = \trim($text);
		
		if (null == $text) {
			return 0;
		}
		
		return \count(\explode(' ', (string) u($text)->collapseWhitespace()));
	}
}
