<?php

namespace App\Service;

use App\Contract\Some\SomeServiceInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractStringService implements SomeServiceInterface
{
    public function __construct(
        public ?LoggerInterface $one = null,
        public $two = null,
    ) {
    }
}
