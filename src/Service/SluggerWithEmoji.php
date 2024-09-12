<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\AbstractUnicodeString;

class SluggerWithEmoji implements SluggerInterface
{
    public function __construct(private $inner)
    {
        $this->inner = $inner->withEmoji('github');
    }

    public function slug(string $string, string $separator = '-', ?string $locale = null): AbstractUnicodeString
    {
        return $this->inner->slug(...\func_get_args());
    }
}
