<?php

namespace App\ValueResolver;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use App\Service\RegexService;
use App\Service\ExpressionLanguage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//TODO: CarbonValueResolver separate now and nowModified logic
/**
* Usage: Write the following over your controller method to modify nowModified
*
* PATTERN:
* @var <FQCN|CN> $nowModified<CAN_PUT_SOME_TEXT> <SIGN><NUMBER><CARBON_PROPERTY>
*
* @var Carbon $nowModified +1day
* @var Carbon $nowModifiedSub2Day -2day
* @var Carbon $nowModifiedMult2Month *2month
* @var Carbon $nowModifiedDiv2Month /2month (Means divide the current number of month by 2)
*/
class CarbonValueResolver implements ValueResolverInterface
{
    private ?string $name;
    private ?string $type;

    public function __construct(
        private readonly RegexService $regexService,
        private readonly PropertyAccessorInterface $pa,
        private readonly ExpressionLanguage $expr,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function resolve(
        Request $request,
        ArgumentMetadata $argument,
    ): array {

        $this->name = $argument->getName();
        $this->type = $argument->getType();

        $now = Carbon::now('UTC');

        if ('now' === $this->name) {
            return $this->resolveNowName($now, $request, $argument);
        }

        if (\str_starts_with($this->name, 'nowModified')) {
            return $this->resolveNowModifiedName($now, $request, $argument);
        }

        return [];
    }

    private function resolveNowName(
        Carbon $now,
        Request $request,
        ArgumentMetadata $argument,
    ): array {
        if ($this->isNotCabonType()) {
            return [];
        }
        return [$now];
    }

    private function resolveNowModifiedName(
        Carbon $now,
        Request $request,
        ArgumentMetadata $argument,
    ): array {
        if ($this->isNotCabonType()) {
            return [];
        }
        $controllerArray = \explode('::', $argument->getControllerName());
        $refl = new \ReflectionMethod(...$controllerArray);
        $this->modifyCarbonWithDocComment($now, $refl->getDocComment());
        return [$now];
    }

    private function isNotCabonType(): bool
    {
        if (
            Carbon::class !== $this->type
            && !\is_subclass_of($this->type, Carbon::class, true)
        ) {
            return true;
        }
        return false;
    }

    private function modifyCarbonWithDocComment(Carbon $carbon, string $docBlock): self
    {

        $modifiers = $this->getParsedDops($docBlock);

        foreach ($modifiers as $modifier) {
            $value = $this->pa->getValue($modifier, '[value?]');
            $operator = $this->pa->getValue($modifier, '[operator?]');
            $dateTimeProperty = $this->pa->getValue($modifier, '[dateTimeProperty?]');

            $allConstraints = new Constraints\All(
                new Constraints\NotNull(),
            );

            //###> WAY 1 returns: TRUE or FALSE ###
            $allNotNull = Validation::createIsValidCallable($allConstraints);
            $atLeastOneOfNull = !$allNotNull([$value, $operator, $dateTimeProperty]);
            //###< WAY 1 returns: TRUE or FALSE ###

            //###> WAY 2 returns: ConstraintViolationList ###
            /*
            $allNotNull = $this->validator->validate(
                [$value, $operator, $dateTimeProperty],
                $allConstraints,
            );
            $atLeastOneOfNull = 0 < \count($allNotNull);
            */
            //###< WAY 2 returns: ConstraintViolationList ###

            if ($atLeastOneOfNull) {
                continue;
            }

            $carbonPath = \sprintf(
                'carbon.%s',
                $dateTimeProperty,
            );
            $expression = \sprintf(
                '%s %s value',
                $carbonPath,
                $operator,
            );
            $value = $this->expr->evaluate(
                $expression,
                [
                    'value' => $value,
                    'carbon' => $carbon,
                ]
            );
            if (\is_float($value)) {
                $value = (int) \round($value);
            }
            $this->pa->setValue($carbon, $dateTimeProperty, $value);
        }
        return $this;
    }

    private function getParsedDops(string $input): array
    {
        $output = \trim($input);

        $matchAllDocBlock1 = [];
        $regex = \sprintf(
            '~@var\s*(?:%s|%s)\s*%s\s*(?:(?<operator>[+\-*/])(?<value>[0-9]+)\s*(?<dateTimeProperty>[a-zA-Zа-яА-Я]+)).*\(?$~umi',
            $this->regexService->getEscapedStrings($this->type),
            'Carbon',
            '\$' . $this->name,
        );
        \preg_match_all($regex, $output, $matchAllDocBlock1, flags: \PREG_SET_ORDER);
        $output = $matchAllDocBlock1;
        return \is_array($output) ? $output : [];
    }
}
