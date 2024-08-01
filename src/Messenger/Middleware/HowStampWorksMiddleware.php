<?php

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use App\Messenger\Stamp\StopPropagationStamp;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\RouterContextStamp;
use Symfony\Component\Messenger\Stamp\HandlerArgumentsStamp;

class HowStampWorksMiddleware implements MiddlewareInterface
{
	private static $idx = 0;
	
	/**
	* @var $envelope sync    when called by worker: SentStamp ReceivedStamp
	* @var $envelope async   when called by worker: ReceivedStamp ONLY
	*/
    public function handle(
        Envelope $envelope,
        StackInterface $stack,
    ): Envelope {
		\dump('GOT MIDDLEWARE', $envelope->all());
		
		/**
		* @var $responseEnvelope sync    SentStamp ReceivedStamp
		* @var $responseEnvelope async   ReceivedStamp ONLY
		*/
		$responseEnvelope = $stack->next()->handle($envelope, $stack);
		
		\dump('AFTER ALL MIDDLEWARES', $responseEnvelope);

        return $responseEnvelope;
    }
}
