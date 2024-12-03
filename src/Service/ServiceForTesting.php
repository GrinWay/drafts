<?php

namespace App\Service;

use Symfony\Contracts\Translation\LocaleAwareInterface;

class ServiceForTesting implements LocaleAwareInterface
{
    private string $locale;

    /**
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
