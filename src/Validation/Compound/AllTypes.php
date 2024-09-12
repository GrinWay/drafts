<?php

namespace App\Validation\Compound;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Attribute\HasNamedArguments;

// TODO: AllTypes
/**
* Usage: $isUploadedFiles = Validation::createIsValidCallable($uploadedFile, new AllTypes(UploadedFile::class));
*/
#[\Attribute]
class AllTypes extends Compound
{
    public bool $notBlank = false;
    public readonly string $class;

    #[HasNamedArguments]
    public function __construct(
        string $class,
        ?bool $notBlank = null,
        mixed $options = null,
    ) {
        $this->class = $class;
        $this->notBlank = $notBlank ?? $this->notBlank;

        parent::__construct($options);
    }

    protected function getConstraints(array $options): array
    {
        $constraints = [
            new Constraints\When(
                expression: 'is_array(value)',
                constraints: [
                    new Constraints\All([
                        new Constraints\Type($this->class),
                    ]),
                ]
            ),
            new Constraints\When(
                expression: 'not is_array(value)',
                constraints: [
                    new Constraints\Type($this->class),
                ],
            ),
        ];

        if (true === $this->notBlank) {
            $constraints[] = new Constraints\NotBlank();
        }

        return $constraints;
    }
}
