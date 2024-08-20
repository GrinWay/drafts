<?php

namespace GrinWay\GenericParts\Contracts;

use Symfony\Component\HttpFoundation\{
    Response
};

interface GSJsonResponseInterface
{
    ###> CAN OVERRIDE IT ###
    public const MESSAGE        = self::class;
    public const HTTP_CODE      = Response::HTTP_OK;
    ###< CAN OVERRIDE IT ###
}
