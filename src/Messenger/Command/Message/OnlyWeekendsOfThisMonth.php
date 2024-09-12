<?php

namespace App\Messenger\Command\Message;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

class OnlyWeekendsOfThisMonth implements MessageHasSyncTransportInterface
{
    public function __construct(
        public readonly bool $includePassed,
    ) {
    }
}
