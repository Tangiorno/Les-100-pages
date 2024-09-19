<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class U
{
    public static function p($var): void
    {
        if ($var === true or $var === false) {
            echo $var ? 'true' : 'false';
            return;
        }

        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    #[NoReturn] public static function pd($var): void
    {
        self::p($var);
        die();
    }
}