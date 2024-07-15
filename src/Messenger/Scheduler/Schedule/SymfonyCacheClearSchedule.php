<?php

namespace App\Messenger\Scheduler\Schedule;

use Symfony\Component\Process\Messenger\RunProcessMessage;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Generator\MessageContext;
use Symfony\Component\Scheduler\Trigger\CallbackMessageProvider;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;
use Symfony\Component\Scheduler\Schedule;
use App\Messenger\Scheduler\Trigger\MondayOnlyTrigger;
use Symfony\Component\Scheduler\Trigger\PeriodicalTrigger;

/*
#[AsPeriodicTask(
    frequency:  '30 second',
    schedule:   'symfony-cache-clear',
    arguments:  [
        'locale' => '%env(APP_LOCALE)%',
    ],
)]
*/
class SymfonyCacheClearSchedule
{
    public function __construct(
        protected readonly MessageBusInterface $bus,
    ) {
    }

    public function __invoke(string $locale): void
    {
        $this->bus->dispatch(
            new RunCommandMessage('cache:clear'),
        );
        /*
        $this->bus->dispatch(
            new RunProcessMessage(
                ['mkdir', './New Dir'],
                cwd: 'C:/Users/son5-/Desktop',
            )
        );
        */
    }
}
