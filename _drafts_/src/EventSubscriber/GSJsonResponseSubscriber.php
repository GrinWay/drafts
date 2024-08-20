<?php

namespace GrinWay\GenericParts\EventSubscriber;

use GrinWay\GenericParts\Contracts\{
    AbstractGSJsonResponse
};
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use GrinWay\GenericParts\Contracts\{
    AbstractGSJsonAnswer,
    AbstractGSException
};
use GrinWay\GenericParts\Service\{
    GrinWayArrayService
};

use function Symfony\Component\String\u;

use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\{
    ViewEvent,
    ExceptionEvent
};
use Symfony\Component\HttpKernel\KernelEvents;

class GSJsonResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private $t,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult           = $event->getControllerResult();

        if (!$controllerResult instanceof AbstractGSJsonResponse) {
            return;
        }

        $responseData       = [
            'message'   => $this->getMessageWithParams($controllerResult),
            'http_code' => $httpCode = $controllerResult->getHttpCode(),
        ];

        $this->endProcess($event, $responseData, $httpCode);
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception          = $event->getThrowable();

        if (!$exception instanceof AbstractGSException) {
            return;
        }

        $responseData       = [
            'error'     => [
                'message'   => $this->getMessageWithParams($exception),
                'http_code' => $httpCode = $exception->getHttpCode(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'code'      => $code = $exception->getCode(),
            ],
        ];

        $this->endProcess($event, $responseData, $httpCode);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION     => 'onKernelException',
            KernelEvents::VIEW          => 'onKernelView',
        ];
    }

    //###> HELPER ###

    private function endProcess(
        Event $event,
        $responseData,
        int $httpCode,
    ): void {
        $event->setResponse($this->getJsonResponse($responseData, $httpCode));
        $event->stopPropagation();
    }

    private function getJsonResponse($data, int $httpCode): JsonResponse
    {
        $response           = new JsonResponse(
            $data,
            $httpCode,
        );
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);
        return $response;
    }

    private function getMessageWithParams(object $response): string
    {
        $resultMessage          = $this->t->trans($response->getMessage(), domain: 'grin_way_generic_parts');

        if (!empty($responseParams = $response->getParams())) {
            $resultMessage          = (
                (string) u($resultMessage)->ensureEnd(': ')
            ) . '[' . GrinWayArrayService::getKeyValueString($responseParams) . ']';
        }

        return $resultMessage;
    }
}
