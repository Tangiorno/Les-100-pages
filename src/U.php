<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class U
{
    public static function p(): void
    {
        foreach (func_get_args() as $var) {
            if ($var === true or $var === false) {
                echo $var ? 'true' : 'false';
                return;
            }
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
    }

    #[NoReturn] public static function pd(): void
    {
        foreach (func_get_args() as $var) {
            self::p($var);
        }
        die();
    }
}