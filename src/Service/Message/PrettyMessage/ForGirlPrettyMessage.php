<?php

namespace App\Service\Message\PrettyMessage;

use App\Attribute\NewClosureDefinitionWithTag;

#[NewClosureDefinitionWithTag('app.pretty_message', 'for_girl')]
class ForGirlPrettyMessage
{
    public function __invoke(): string
    {
        return 'hey_girl';
    }
}
