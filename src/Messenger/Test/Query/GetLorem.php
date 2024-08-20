<?php

namespace App\Messenger\Test\Query;

use App\Messenger\Query\QueryInterface;

class GetLorem implements QueryInterface
{
    public function __construct(
        protected int $num = 100,
    ) {
        if ($num < 5) {
            $this->num = 5;
        }
    }

    public function getNum(): int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }
}
