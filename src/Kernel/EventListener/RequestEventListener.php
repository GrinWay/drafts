<?php

namespace App\Kernel\EventListener;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
class RequestEventListener
{
    public function __construct(private readonly string $projectDir)
    {
    }

    /**
     * ENTRYPOINT
     */
    public function __invoke(RequestEvent $event): void
    {
        $this->loadEnv();
    }

    private function loadEnv(): void
    {
        return;
        $dotEnv = new DotEnv();
        $dotEnv->load($this->projectDir . '/txt.txt');
    }
}
