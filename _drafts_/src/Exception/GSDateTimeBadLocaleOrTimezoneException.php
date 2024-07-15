<?php

namespace GrinWay\GenericParts\Exception;

use GrinWay\GenericParts\Contracts\AbstractGSException;
use Symfony\Component\HttpFoundation\{
    Response
};

class GSDateTimeBadLocaleOrTimezoneException extends AbstractGSException
{
    public const MESSAGE = 'exception.bad_locale_or_timezone';
}
