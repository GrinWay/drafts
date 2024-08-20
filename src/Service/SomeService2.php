<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Psr\Log\LoggerInterface;

class SomeService2 extends AbstractStringService
{
    public function __construct(
        $one = null,
        $two = null,
        //
        private \Closure|LoggerInterface|string $logger,
    ) {
        parent::__construct(
            one: $one,
            two: $two,
        );
    }

    public function __invoke()
    {
        if (is_string($s = $this->getLogger())) {
            \dump($s);
            return;
        }

        $this->getLogger()->notice('LOG', ['__METHOD__' => __METHOD__]);
    }

    public function getGenerator(...$args): \Generator
    {
        yield ['data' => 0];
        yield ['data' => 1];
        yield ['data' => 2];
    }

    //###> HELPER ###

    private function getLogger(): LoggerInterface|string
    {
        return ($this->logger instanceof \Closure) ? ($this->logger = ($this->logger)()) : $this->logger;
    }
}
