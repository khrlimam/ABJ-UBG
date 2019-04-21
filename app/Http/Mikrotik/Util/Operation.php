<?php


namespace App\Http\Mikrotik\Util;


class Operation
{
    public static function isSuccess($returned)
    {
        if (is_array($returned)) return count($returned) == 0 && !key_exists('!trap', $returned);
        return FALSE != $returned;
    }
}