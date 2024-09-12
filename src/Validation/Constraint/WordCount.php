<?php

namespace App\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use App\Validation\Validator\WordCountValidator;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class WordCount extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        public readonly int $min,
        public readonly int $max,
        public readonly string $exactlyMessage = 'Кол-во слов предложения {passed_len}, а должно быть точно {must_len}',
        public readonly string $minMessage = 'Кол-во слов предложения {passed_len}, а должно быть не меньше {must_len}',
        public readonly string $maxMessage = 'Кол-во слов предложения {passed_len}, а должно быть не больше {must_len}',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(
            groups: $groups,
            payload: $payload,
        );
    }

    public function validatedBy(): string
    {
        return WordCountValidator::class;
    }
}
