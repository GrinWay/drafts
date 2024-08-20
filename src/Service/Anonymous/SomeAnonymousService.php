<?php

namespace App\Service\Anonymous;

use Symfony\Component\Messenger\MessageBusInterface;

class SomeAnonymousService
{
    public function __construct()
    {
        \dump(__METHOD__);
    }
}
