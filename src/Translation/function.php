<?php

namespace App\Translation;

function t(string $message, array $parameters = [], ?string $domain = null): TranslatableMessage
{
	return new TranslatableMessage($message, $parameters, $domain);
}