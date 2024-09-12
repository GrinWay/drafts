<?php

namespace App\Contract\EventListener\Form;

use Symfony\Contracts\EventDispatcher\Event;

interface InvokeableFormEventListenerInterface
{
    public function __invoke(Event $e): void;
}
