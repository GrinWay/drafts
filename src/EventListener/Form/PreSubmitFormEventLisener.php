<?php

namespace App\EventListener\Form;

use function Symfony\Component\String\u;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Contract\EventListener\Form\InvokeableFormEventListenerInterface;

#[AsEventListener(
    event: FormEvents::PRE_SUBMIT,
)]
class PreSubmitFormEventLisener
{
    public function __invoke(
        FormEvent $e,
    ): void {
        \dump('__GLOBAL__ PRE SUBMIT EVENT');
    }
}
