<?php

namespace App\Type\Media;

class MediaType
{
    public const IMAGE = 'image';
    public const AVATAR = 'avatar';

    public const TYPES = [
        'IMAGE'        => self::IMAGE,
        'AVATAR'       => self::AVATAR,
    ];
}
