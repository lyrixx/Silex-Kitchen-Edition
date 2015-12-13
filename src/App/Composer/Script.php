<?php

namespace App\Composer;

class Script
{
    public static function install()
    {
        @mkdir('var/cache', 0777);
        @mkdir('var/log', 0777);
        chmod('bin/console', 0755);
    }
}
