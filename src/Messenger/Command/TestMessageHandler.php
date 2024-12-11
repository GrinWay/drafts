<?php

namespace App\Messenger\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestMessageHandler
{
    public function __invoke(TestMessage $message)
    {
        \dump('Command was handled');
    }
}
