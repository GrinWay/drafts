<?php

namespace App\Test\DataProvider;

class LocaleProvider {
	public static function locales(): array {
		return [
			'Russian language' => ['ru'],
			'English language' => ['en'],
		];
	}
}