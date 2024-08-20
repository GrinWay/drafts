<?php

namespace GrinWay\GenericParts\Exception;

use GrinWay\GenericParts\Contracts\AbstractGSException;
use Symfony\Component\HttpFoundation\{
    Response
};

class GSSerializerParseException extends AbstractGSException
{
    public const MESSAGE = 'exception.serializer_parse';
}
