<?php

namespace App\Messenger\Command\Message;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

class HowStampWorks// implements MessageHasSyncTransportInterface
{
    public function __construct()
    {
    }

    public function __serialize(): array
    {
        \dump(__METHOD__);
        return [];
    }

    public function __unserialize(array $data): void
    {
        \dump(__METHOD__);
    }
}
