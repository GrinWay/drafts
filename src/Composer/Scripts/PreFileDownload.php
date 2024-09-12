<?php

namespace App\Composer\Scripts;

use Composer\Plugin\PreFileDownloadEvent;

class PreFileDownload
{
    public static function execute(PreFileDownloadEvent $event): void
    {
        echo __METHOD__ . \PHP_EOL;
        $httpDownloader = $event->getHttpDownloader();
        /*
        \var_dump(get_debug_type($event));
        exit;
        */
    }
}
