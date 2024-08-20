<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponse
{
    public function __construct(protected readonly mixed $content)
    {
    }

    abstract function getResponse(): Response;
}
