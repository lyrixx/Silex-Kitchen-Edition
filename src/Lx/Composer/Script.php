<?php

namespace Lx\Composer;

use Composer\Script\Event;

class Script
{

    public static function postInstall(Event $event)
    {
        chmod('cache', 0777);
        chmod('log', 0777);
        chmod('web/assets', 0777);
    }

}
