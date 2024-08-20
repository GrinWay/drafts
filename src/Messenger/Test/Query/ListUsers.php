<?php

namespace App\Messenger\Test\Query;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

class ListUsers implements MessageHasSyncTransportInterface
{
    public function __construct()
    {
    }
}
