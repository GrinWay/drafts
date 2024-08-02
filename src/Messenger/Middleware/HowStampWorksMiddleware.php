<?php

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use App\Messenger\Stamp\StopPropagationStamp;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Stamp\RouterContextStamp;
use Symfony\Component\Messenger\Stamp\HandlerArgumentsStamp;

class HowStampWorksMiddleware implements MiddlewareInterface
{
	private static $idx = 0;
	
    public function handle(
        Envelope $envelope,
        StackInterface $stack,
    ): Envelope {
		$responseEnvelope = null;
		
		/*
		if (self::$idx++ > 0) {
			return $envelope;
		}
		*/
		
		\dump('ENTERED INTO CUSTOM MIDDLEWARE');
		//$envelope = $envelope->with(new DelayStamp(1));
		$responseEnvelope = $stack->next()->handle($envelope, $stack);
		\dump(\get_debug_type($responseEnvelope->getMessage()), $responseEnvelope);
		
        return $responseEnvelope ?? $stack->next()->handle($envelope, $stack);
    }
}
