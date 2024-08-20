<?php

namespace App\Messenger\Command\Message;

use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;
use Symfony\Component\Process\Messenger\RunProcessMessage;

class SyncRunProcessMessage extends RunProcessMessage implements MessageHasSyncTransportInterface {}