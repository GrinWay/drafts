<?php

namespace App\Messenger\Test\Query;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetLoremHandler
{
    public function __construct(
        protected $faker,
    ) {
    }

    public function __invoke(GetLorem $query)
    {
        return $this->faker->text($query->getNum());
    }
}
