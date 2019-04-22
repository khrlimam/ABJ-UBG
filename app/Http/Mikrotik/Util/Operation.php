<?php


namespace App\Http\Mikrotik\Util;


use KhairulImam\ROSWrapper\RollbackedException;
use KhairulImam\ROSWrapper\Wrapper;

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

    public static function getUnusedInterfaceAlongWithIpAddress(Wrapper $mikrotik)
    {
        $usedInterfaces = collect($mikrotik->run("ip dhcp-server print"))->map(function ($item) {
            return $item['interface'];
        })->toArray();
        $allInterfaces = collect($mikrotik->run("interface print"))->filter(function ($item) use ($usedInterfaces) {
            return !in_array($item['name'], $usedInterfaces);
        });
        $ipAdresses = collect($mikrotik->run("ip address print"));
        $interfaces = $allInterfaces->map(function ($interface) use ($ipAdresses) {
            $gotIp = $ipAdresses->filter(function ($interfaceIp) use ($interface) {
                return $interfaceIp['interface'] == $interface['name'];
            });
            if ($gotIp->isNotEmpty())
                return array_merge($interface, $gotIp->first());
            return array_merge($interface, ['address' => '-']);
        });
        return $interfaces;
    }

    public static function getAvailableInterfacesAlongWithIpAddresses(Wrapper $mikrotik)
    {
        $allInterfaces = collect($mikrotik->run("interface print"));
        $ipAdresses = collect($mikrotik->run("ip address print"));
        $interfaces = $allInterfaces->map(function ($interface) use ($ipAdresses) {
            $gotIp = $ipAdresses->filter(function ($interfaceIp) use ($interface) {
                return $interfaceIp['interface'] == $interface['name'];
            });
            if ($gotIp->isNotEmpty())
                return array_merge($interface, $gotIp->first());
            return array_merge($interface, ['address' => '-']);
        });
        return $interfaces;
    }

}