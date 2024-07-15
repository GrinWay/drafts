<?php

namespace GrinWay\GenericParts\Messenger\KernelBootstrap\Command\Handler;

use GrinWay\GenericParts\Messenger\AbstractHandler;
use GrinWay\GenericParts\Messenger\KernelBootstrap\Command\InitCarbon;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Carbon\{
    Carbon,
    CarbonImmutable,
    Translator,
    Doctrine\DateTimeDefaultPrecision
};

#[AsMessageHandler]
class InitCarbonHandler
{
    public function __construct(
        private $locale,
        //private $debugLogger,
    ) {
    }

    public function __invoke(InitCarbon $_0)
    {
        $this->setCarbonMacro();
        $this->modifyTranslations();
        DateTimeDefaultPrecision::set(7);
    }

    private function setCarbonMacro(): void
    {
        Carbon::mixin(new \GrinWay\GenericParts\Carbon\SourceMacros());
        CarbonImmutable::mixin(new \GrinWay\GenericParts\Carbon\SourceMacros());
    }

    private function modifyTranslations(): void
    {
        //$this->debugLogger->info($this->locale);
        Translator::get($this->locale)->setTranslations([
            // unit
            //'day' => ':count дня|:count дней',
            // key word
            //'before' => static fn($d) => 'до ' . $d,
        ]);
    }
}
