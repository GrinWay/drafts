<?php

namespace App\Service\Form;

class CsrfToken
{
    public function __construct(
        private $name = null,
    ) {
    }

    public function get()
    {
        return $this->requestStack->getSession()?->all();
    }
}
