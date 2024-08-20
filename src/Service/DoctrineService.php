<?php

namespace App\Service;

use GrinWay\Service\Service\DoctrineService as GrinWayDoctrineService;
use Symfony\Contracts\Translation\TranslatorInterface;

class DoctrineService extends GrinWayDoctrineService
{
    public function __construct(
        TranslatorInterface $t,
    ) {
        parent::__construct(
            t: $t,
        );
    }
}
