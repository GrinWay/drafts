<?php

namespace App\Service\Form;

use App\Repository\TestForTestFormTypeRepository;
use App\Service\StringService;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use App\Entity\Product\Product;
use App\Type\Note\NoteType;
use Symfony\Component\Validator\Constraints;

class TestForTestFormType extends AbstractTestForTestFormType
{
    function __construct(
        mixed $name = [NoteType::WARNING],
        public mixed $price = null,
        public mixed $email = null,
        public ?Product $product = null,
        public mixed $rubric = null,
        public mixed $createdAt = null,
        public mixed $timezone = '+02:00',
    ) {
        parent::__construct($name);

        $this->createdAt ??= new CarbonImmutable('1970-01-01 00:00:00');
    }
}
