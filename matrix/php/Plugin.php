<?php

namespace TheMatrix;

class Plugin
{
    const ID = 'matrix';

    public static function getFullPathToFile($filename)
    {
        return implode('/', [ GSPLUGINPATH, self::ID, $filename ]);
    }
}