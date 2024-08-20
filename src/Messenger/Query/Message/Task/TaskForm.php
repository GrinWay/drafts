<?php

namespace App\Messenger\Query\Message\Task;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

class TaskForm implements MessageHasSyncTransportInterface
{
    public function __construct(
        public readonly ?array $findOneBy = null,
    ) {
    }
}
