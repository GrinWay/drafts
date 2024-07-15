<?php

namespace App\Messenger\Query\Message\Task;

use GrinWay\WebApp\Contract\Messenger\HasSyncTransportInterface;

class TaskForm implements HasSyncTransportInterface
{
    public function __construct(
        public readonly ?array $findOneBy = null,
    ) {
    }
}
