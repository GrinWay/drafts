<?php

namespace App\Service;

use Symfony\Component\Mime\MimeTypes;

//TODO: MimeUtil
class MimeUtil
{
    /*
     * @return  (Get extension by mime type)
     */
    public static function getExt(string $mimeType, bool $first = true): array|string|null
    {
        $extension = null;
        $extensions = (new MimeTypes())->getExtensions($mimeType);

        if (true === $first) {
            if (0 === \count($extensions)) {
                $extension = null;
            } else {
                $extension = \reset($extensions);
            }
        }

        $extension ??= $extensions;

        return $extension;
    }
}
