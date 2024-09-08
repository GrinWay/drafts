<?php

namespace App\Notification\BrowserNotification;

use App\Type\Note\NoteType;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\FlashMessage\AbstractFlashMessageImportanceMapper;
use Symfony\Component\Notifier\FlashMessage\FlashMessageImportanceMapperInterface;

class AppFlashMessageImportanceMapper extends AbstractFlashMessageImportanceMapper implements FlashMessageImportanceMapperInterface
{
    protected const IMPORTANCE_MAP = [
        Notification::IMPORTANCE_URGENT => NoteType::ERROR,
        Notification::IMPORTANCE_HIGH => 'notification',
        Notification::IMPORTANCE_MEDIUM => NoteType::NOTICE,
        Notification::IMPORTANCE_LOW => 'secondary',
    ];
}
