<?php

namespace GrinWay\GenericParts\Controller;

use function Symfony\Component\String\u;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use Symfony\Component\Routing\Annotation\Route;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

abstract class GSAbstractController extends AbstractController
{
    public function __construct(
        protected $tzSessionName,
    ) {
    }
}
