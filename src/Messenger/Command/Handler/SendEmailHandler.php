<?php

namespace App\Messenger\Command\Handler;

use Psr\Log\LoggerInterface;
use App\Messenger\Command\Message\SendEmail;
use App\Messenger\AbstractHandler;

class SendEmailHandler extends AbstractHandler
{
    public function __invoke(SendEmail $email): void
    {
        //$this->service('logger')?->info(__METHOD__, ['message' => $email()]);
        $this->logger()->info(__METHOD__, ['message' => $email()]);
        //$name = $this->faker()->name;
    }
}
