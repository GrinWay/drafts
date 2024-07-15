<?php

namespace GrinWay\GenericParts\Exception;

use Symfony\Component\HttpFoundation\{
    Response
};
use GrinWay\GenericParts\Contracts\AbstractGSException;

class GSPOSTRequestDoesnotContainParameter extends AbstractGSException
{
    public const MESSAGE        = 'exception.post_doesnot_contain';
    public const HTTP_CODE      = Response::HTTP_BAD_REQUEST;
}
