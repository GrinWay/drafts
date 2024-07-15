<?php

namespace App\Service\Validator;

class TextValidatorNormalizer {
	public static function trim($v): string {
		if (\is_array($v)) {
			return \array_map(static fn($e) => self::normalize($e), $v);
		}
		return \trim((string) $v);
	}
}