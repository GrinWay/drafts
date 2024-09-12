<?php

namespace App\Messenger\Command\Message;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

class Hours implements MessageHasSyncTransportInterface
{
    public function __construct(
        public readonly bool $includePassed,
    ) {
    }
}
