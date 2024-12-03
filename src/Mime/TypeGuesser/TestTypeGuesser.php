<?php

namespace App\Mime\TypeGuesser;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Mime\MimeTypeGuesserInterface;

//#[AutoconfigureTag('mime.mime_type_guesser')]
class TestTypeGuesser implements MimeTypeGuesserInterface
{
	public function isGuesserSupported(): bool
    {
        return true;
    }

    public function guessMimeType(string $path): ?string
    {
        return 'обычный файл';
    }
}