<?php

namespace App\EventListener\Shop;

use App\Messenger\Event\Message\Shop\PriceWasDecreased;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Type\Event\ShopEvent;

class PriceWasDecreasedEventListener
{
    #[AsEventListener(event: PriceWasDecreased::class, priority: 1)]
    public function h1(PriceWasDecreased $event): void
    {
        \dump('PRICE WAS DECREASED EVENT HAPPENED 1H');
        $event->stopPropagation();
    }

    #[AsEventListener(event: PriceWasDecreased::class, priority: 0)]
    public function h2(PriceWasDecreased $event): void
    {
        \dump('PRICE WAS DECREASED EVENT HAPPENED 2H');
    }
}
