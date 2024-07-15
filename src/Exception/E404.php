<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class E404 extends \Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = "EXCEPTION 404",
        int $code = 404,
        ?Throwable $previous = null
    ) {
        $this->code = $code;

        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous,
        );
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
