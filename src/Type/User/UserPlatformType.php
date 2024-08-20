<?php

namespace App\Type\User;

enum UserPlatformType: string
{
    case PC                 = 'pc';
    case MOBILE             = 'mobile';
    case TV                 = 'tv';
    case PLAY_STATION       = 'ps';
    case X_BOX              = 'x-box';
}
