<?php

namespace App\Resources;

use App\Translation\TranslatableMessage;

function t(string $message, array $parameters = [], ?string $domain = null): TranslatableMessage
{
    return new TranslatableMessage($message, $parameters, $domain);
}
