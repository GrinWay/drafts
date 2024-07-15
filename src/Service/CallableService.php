<?php

namespace App\Service;

class CallableService
{
    public function __invoke(): mixed
    {
        return 'CALLED';
    }
}
