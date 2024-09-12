<?php

namespace App\Form\TypeGuesser;

use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class PhpDocTypeGuesser implements FormTypeGuesserInterface
{
    public const DOC_BLOCK_PREFIX = '@guess';

    public function __construct(
        private readonly PropertyInfoExtractorInterface $extractor,
    ) {
    }

    //###> FORM TYPE ###
    /**
    * @guess textarea $name
    */
    public function guessType(string $class, string $property): ?TypeGuess
    {
        $guessDockBlock = $this->getVarDocBlock($class, $property);

        if (null === $guessDockBlock) {
            return null;
        }

        return match ($guessDockBlock) {
            'string' => new TypeGuess(FormType\TextType::class, [], Guess::LOW_CONFIDENCE),
            'text', 'textarea' => new TypeGuess(FormType\TextareaType::class, [], Guess::LOW_CONFIDENCE),
            'int', 'integer' => new TypeGuess(FormType\IntegerType::class, [], Guess::LOW_CONFIDENCE),
            'float', 'double', 'real' => new TypeGuess(FormType\FloatType::class, [], Guess::LOW_CONFIDENCE),
            'bool', 'boolean' => new TypeGuess(FormType\CheckboxType::class, [], Guess::LOW_CONFIDENCE),
            'choice' => new TypeGuess(FormType\ChoiceType::class, [], Guess::LOW_CONFIDENCE),
            default => new TypeGuess(FormType\TextType::class, [], Guess::LOW_CONFIDENCE),
        };
    }
    //###< FORM TYPE ###


    //###> HTML5 ###
    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return null;
    }
    //###< HTML5 ###


    //###> HELPER ###

    private function getVarDocBlock(string $class, string $property): ?string
    {
        $dockBlock = null;
        $propRefl = new \ReflectionProperty($class, $property);
        $docComment = $propRefl->getDocComment();
        if (false === $docComment) {
            return null;
        }
        $matches = [];
        \preg_match('~' . self::DOC_BLOCK_PREFIX . '(?<v>.+)\$' . $property . '~im', $docComment, $matches);
        $dockBlock = \trim($matches['v']);
        return $dockBlock;
    }
}
