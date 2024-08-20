<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class HtmlResponse extends AbstractResponse
{
    function getResponse(): Response
    {
        return new Response($this->content);
    }
}
