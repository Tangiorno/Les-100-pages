<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class U
{
    #[NoReturn] public static function pd(...$args): void
    {
        foreach ($args as $var) {
            self::p($var);
        }
        die();
    }

    public static function p(...$args): void
    {
        foreach ($args as $var) {
            if ($var === true || $var === false) {
                echo $var ? 'true' : 'false';
                continue;
            }
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
    }
}