<?php


namespace App\Http\Mikrotik\Util;


use KhairulImam\ROSWrapper\RollbackedException;

class Operation
{
    public static function isSuccess($returned)
    {
        if (is_array($returned)) return count($returned) == 0 && !key_exists('!trap', $returned);
        return FALSE != $returned;
    }

    public static function getPrettyMessage(RollbackedException $exception)
    {
        $prettyMessage = "Mohon maaf, terjadi kesalahan ketika menjalankan proses <b>" . $exception->getFailedCommandName() . "</b><br>";
        $prettyMessage .= "Error terjadi dengan alasan <b>" . $exception->getReason() . "</b><br>";
        $prettyMessage .= "Proses berikut telah di rollback:<br>";
        $prettyMessage .= "<ol>";
        foreach ($exception->getRollbackedCommands() as $rolledBack) {
            $prettyMessage .= "<li>" . $rolledBack->name() . "</li>";
        }
        $prettyMessage .= "</ol>";
        return $prettyMessage;
    }

    public static function transformStateIntoAcceptableCommand($data)
    {
        return collect($data)->map(function ($item) {
            switch ($item) {
                case 'false':
                    return 'no';
                case 'true':
                    return 'yes';
                default:
                    return $item;
            }
        })->all();
    }

}