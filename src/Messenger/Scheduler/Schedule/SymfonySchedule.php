<?php

namespace App\Messenger\Scheduler\Schedule;

use Symfony\Component\Scheduler\Event\PostRunEvent;
use Symfony\Component\Scheduler\Event\PreRunEvent;
use Symfony\Component\Scheduler\Generator\MessageContext;
use Symfony\Component\Scheduler\Trigger\CallbackMessageProvider;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use App\Messenger\Scheduler\Trigger\MondayOnlyTrigger;
use Symfony\Component\Scheduler\Trigger\PeriodicalTrigger;
use Symfony\Component\Process\Messenger\RunProcessMessage;
use Symfony\Component\Scheduler\Event\FailureEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Cache\CacheInterface;

//#[AsSchedule('app_symfony')]
class SymfonySchedule implements ScheduleProviderInterface
{
    protected array $messagesAndCallbacks = [];
    protected int $cacheClearShouldCancel = 0;
    public const DIR = 'C:/Users/son5-/Desktop/New Dir';

    public const CRON = [
        [
            RunProcessMessage::class,
            [
                [
                    'mkdir',
                    self::DIR,
                ],
            ],
            '5 seconds',
        ],
    ];


    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        $triggers = $this->initSchedule();

        return $this->schedule ??= (new Schedule($this->dispatcher))
            ->with(
                ...$triggers,
            )
            ->stateful($this->cache)
            ->before(function (PreRunEvent $event) {
                $message = $event->getMessage();
                foreach ($this->messagesAndCallbacks as [$_message, $_before_callback]) {
                    if ($message === $_message) {
                        $_before_callback($event);
                    }
                }
            })
            ->after(function (PostRunEvent $event) {
                $message = $event->getMessage();
                foreach ($this->messagesAndCallbacks as [$_message, $_, $_after_callback]) {
                    if ($message === $_message) {
                        $_after_callback($event);
                    }
                }
            })
            ->onFailure(function (FailureEvent $event) {
                $message = $event->getMessage();
                foreach ($this->messagesAndCallbacks as [$_message, $_, $_, $_on_failure_callback]) {
                    if ($message === $_message) {
                        $_on_failure_callback($event);
                    }
                }
            })
            ;
    }

    private function initSchedule(): array
    {
        if ($this->messagesAndCallbacks !== []) {
            return [];
        }

        $triggers = [];

        foreach (self::CRON as [$messageClass, $args, $period]) {
            $message = new $messageClass(...$args);

            $triggers [] = RecurringMessage::trigger(
                new PeriodicalTrigger($period),
                $message,
            );

            $this->messagesAndCallbacks [] = [
                $message,
                function ($event) {
                    $message = $event->getMessage();
                    $messageContext = $event->getMessageContext();
                    $schedule = $event->getSchedule()->getSchedule();

                    if (\is_dir(self::DIR)) {
                        $event->shouldCancel(true);

                        if (++$this->cacheClearShouldCancel > 3) {
                            $schedule->removeById($messageContext->id);
                            $this->cacheClearShouldCancel = 0;
                            return;
                        }
                    }
                },
                fn($e) => $this->cacheClearShouldCancel = 0,
                static fn($e) => $e->getSchedule()->getSchedule()->removeById($e->getMessageContext()->id) && $e->shouldIgnore(true),
            ];
        }

        return $triggers;
    }
}
