<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ErrorController extends AbstractController
{
    public function __invoke(
        $exception,
        $logger,
        $_controller,
    ): Response {
        //$logs = $logger->getLogs();
        $countErrors = $logger->countErrors();
        $trace = $exception->getTraceAsString();

        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'error_controller_name' => $_controller,

            'exception' => $exception,

            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText(),

            'count_errors' => $countErrors,
        ]);
    }
}
