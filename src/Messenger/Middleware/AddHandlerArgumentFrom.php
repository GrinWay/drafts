<?php

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\RouterContextStamp;
use Symfony\Component\Messenger\Stamp\HandlerArgumentsStamp;

/*
    TODO: add grin_way_web_app.messenger.add_handler_argument_from
    $stamps:
        -   id: 'Symfony\Component\Messenger\Stamp\RouterContextStamp'
            methods:
                baseUrl:
                    name: 'getBaseUrl'
                    args: []
                pathInfo:
                    name: 'getPathInfo'
                    args: []
*/
class AddHandlerArgumentFrom implements MiddlewareInterface
{
    public function __construct(
        private readonly array $stamps = [],
    ) {
    }

    public function handle(
        Envelope $envelope,
        StackInterface $stack
    ): Envelope {
        \dump('___TRYING___: "' . __CLASS__ . '"');

        foreach ($this->stamps as [ 'id' => $id ]) {
            $stamp = $envelope->last($id);
            if ($stamp === null) {
                return $stack->next()->handle($envelope, $stack);
            }
        }

        $results = [];

        \dump('>>>ENTERED>>>: "' . __CLASS__ . '"');

        foreach ($this->stamps as [ 'id' => $id, 'methods' => $methods ]) {
            $stamp = $envelope->last($id);

            foreach ($methods as $argumentName => [ 'name' => $method, 'args' => $args ]) {
                if (\method_exists($stamp, $method)) {
                    $results[$argumentName] = $stamp->{$method}($args);
                }
            }
        }

        $envelope = $envelope->with(new HandlerArgumentsStamp($results));

        return $stack->next()->handle($envelope, $stack);
    }
}
