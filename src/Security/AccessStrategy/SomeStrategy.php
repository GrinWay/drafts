<?php

namespace App\Security\AccessStrategy;

use Symfony\Component\Security\Core\Authorization\Strategy\AccessDecisionStrategyInterface;

class SomeStrategy implements AccessDecisionStrategyInterface
{
    public function decide(\Traversable $results): bool
    {
        foreach ($results as $v) {
            \dump($v);
        }
        return false;
    }
}
