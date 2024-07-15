<?php

namespace App\Messenger\Scheduler\Trigger;

use Symfony\Component\Scheduler\Trigger\TriggerInterface;
use Carbon\Carbon;

class MondayOnlyTrigger implements TriggerInterface
{
    public function __construct(
        private TriggerInterface $inner,
    ) {
    }

    public function __toString(): string
    {
        return $this->inner . ' (only monday)';
    }

    public function getNextRunDate(\DateTimeImmutable $run): ?\DateTimeImmutable
    {
        if (!$nextRun = $this->inner->getNextRunDate($run)) {
            return null;
        }

        while (!$this->isMonday($nextRun)) {
            $nextRun = $this->inner->getNextRunDate($nextRun);
        }

        return $nextRun;
    }

    private function isMonday(\DateTimeImmutable $date): bool
    {
        return Carbon::create($date)->isMonday();
    }
}
