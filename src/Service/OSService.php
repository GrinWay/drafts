<?php

namespace App\Service;

use GrinWay\Service\Service\OSService as GrinWayOSService;
use Symfony\Contracts\Service\ResetInterface;

class OSService extends GrinWayOSService implements ResetInterface
{
    public function reset()
    {
        \dump(__METHOD__);
    }
}
