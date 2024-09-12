<?php

namespace App\Twig\Mime;

use Symfony\Bridge\Twig\Mime\NotificationEmail as SymfonyNotificationEmail;
use Symfony\Component\Mime\Header\Headers;

// TODO: current
// C:\Users\user\Desktop\drafts\vendor\symfony\twig-bridge\Mime
// fix headers, subject...
class NotificationEmail extends SymfonyNotificationEmail
{
	public function getPreparedHeaders(): Headers
    {
        return parent::getPreparedHeaders();
    }
}