<?php

namespace GrinWay\GenericParts\Contracts;

use Symfony\Component\HttpFoundation\{
    Response
};

use function Symfony\Component\String\u;

abstract class AbstractGSException extends \Exception implements GSJsonResponseInterface
{
    use GSJsonResponseTrait;

    ###> CAN OVERRIDE IT ###
    public const MESSAGE        = self::class;
    public const HTTP_CODE      = Response::HTTP_INTERNAL_SERVER_ERROR;
    ###< CAN OVERRIDE IT ###

    ###> HELPER ###
}
