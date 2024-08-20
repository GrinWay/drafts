<?php

namespace GrinWay\GenericParts\JsonResponse;

use GrinWay\GenericParts\Contracts\{
    AbstractGSJsonResponse
};
use Symfony\Component\HttpFoundation\{
    Response
};

class GSJsonResponseTimezoneSuccessfullySet extends AbstractGSJsonResponse
{
    public const MESSAGE        = 'json_api_answer.timezone_successfully_set';
}
