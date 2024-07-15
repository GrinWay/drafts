<?php

namespace App\Messenger\Test;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Contract\Messenger\EventBusHandlerInterface;

class TestMessageHandler implements EventBusHandlerInterface
{
    public function __invoke(TestMessage $message): void
    {
        \dump($message);
    }
}
