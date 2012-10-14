<?php

namespace SKE\Composer;

use Composer\Script\Event;

class Script
{

    public static function postInstall(Event $event)
    {
        chmod('resources/cache', 0777);
        chmod('resources/log', 0777);
        chmod('web/assets', 0777);
        chmod('console', 0500);
        exec('php console assetic:dump');
    }

}
