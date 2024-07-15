<?php

namespace App\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use App\Validation\Validator\IsRegexValidator;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class IsRegex extends Constraint {
	
	public readonly string $language;
	public string $message;
	
	#[HasNamedArguments]
	public function __construct(
		?string $language = null,
		?string $message = null,
		?array $groups = null,
		mixed $payload = null,
	) {
		$this->language = $language ? \mb_strtolower($language) : 'php';
		$this->message = $message ?? 'Incorrect {{ language }} regular expression: "{{ regex }}"';
		
		parent::__construct(
			groups: $groups,
			payload: $payload,
		);
    }

	public function validatedBy(): string
    {
        return IsRegexValidator::class;
    }

}